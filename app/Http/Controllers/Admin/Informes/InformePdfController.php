<?php

namespace App\Http\Controllers\Admin\Informes;

use App\Models\Caja\ComprobanteContable;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use Exception, Auth, DB;
use Carbon\Carbon;

class InformePdfController extends Controller
{
    public function index()
    {
        try{
            $agencias = DB::table('agencia')->select('agenid','agennombre')->where('agenactiva', true)->orderBy('agennombre')->get();
            $usuarios = DB::table('usuario as u')
                                ->select('u.usuaid','u.agenid','c.cajanumero', 'c.cajaid', DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                ->join('caja as c', 'c.cajaid', '=', 'u.cajaid')
                                ->where('u.usuaactivo', true)
                                ->orderBy('nombreUsuario')
                                ->get();

            return response()->json(['success' => true, "agencias" => $agencias, "usuarios" => $usuarios]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function comprobanteContable(Request $request)
	{
        $this->validate(request(),['agencia'  => 'required']);

        try {
            $fechaHoraActual  = Carbon::now();
            $fechaActual      = $fechaHoraActual->format('Y-m-d');
            $fechaComprobante = $request->fecha;
            $idUsuario        = $request->usuario;
            $agenciaId        = $request->agencia;
            $cajaId           = $request->caja;

            $comprobanteContableId = DB::table('comprobantecontable as cc')
                                ->select('cc.comconid','cc.comcondescripcion', 'a.agennombre', 'c.cajanumero', 'cc.movcajid',
                                        DB::raw('DATE(cc.comconfechahora) as fechaComprobante'), 
                                        DB::raw("CONCAT(cc.comconanio, cc.comconconsecutivo) as numeroComprobante"),
                                        DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                ->join('agencia as a', 'a.agenid', '=', 'cc.agenid')
                                ->join('caja as c', 'c.cajaid', '=', 'cc.cajaid')
                                ->join('usuario as u', 'u.usuaid', '=', 'cc.usuaid')
                                ->whereDate('cc.comconfechahora', $fechaComprobante)
                                ->where('cc.usuaid', $idUsuario)
                                ->where('cc.agenid', $agenciaId)
                                ->where('cc.cajaid', $cajaId)
                                ->first();

            $dataComprobante = [];
            $success         = false;
            $message         = 'El comprobante contable con los criterios ingresados no existe';
            if($comprobanteContableId){
                $arrayDatos = [ 
                    "nombreUsuario"       => $comprobanteContableId->nombreUsuario,
                    "nuemeroComprobante"  => $comprobanteContableId->numeroComprobante,
                    "fechaComprobante"    => $comprobanteContableId->fechaComprobante,
                    "nombreAgencia"       => $comprobanteContableId->agennombre,
                    "numeroCaja"          => $comprobanteContableId->cajanumero,
                    "conceptoComprobante" => $comprobanteContableId->comcondescripcion,
                    "mensajeImpresion"    => 'Documento impreso el dia '.$fechaHoraActual,
                    "metodo"              => 'S'
                ];

                $generarPdf  = new generarPdf();
                $dataComprobante = $generarPdf->generarComprobanteContable($arrayDatos, MovimientoCaja::obtenerMovimientosContablesPdf($fechaActual, $idUsuario, $agenciaId, $cajaId));
                $success         = true;
                $message         = 'comprobante contable generado con éxito';
            }

            return response()->json(['success' => $success, 'message' => $message, "dataComprobante" => $dataComprobante ]);
        } catch (Exception $error){

            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }
}
