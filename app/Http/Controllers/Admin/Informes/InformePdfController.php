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

            return response()->json(['success' => true, "agencias" => $agencias]);
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
            $fechaComprobante =  $fechaActual;
            $idUsuario        = 2;
            $agenciaId        = $request->agencia;
            $cajaId           = 1;

            $comprobanteContableId = DB::table('comprobantecontable as cc')
                            ->select('cc.comconid', 'cc.movcajid', DB::raw('DATE(cc.comconfechahora) as fechaComprobante'), 
                            DB::raw("CONCAT(cc.comconanio, cc.comconconsecutivo) as numeroComprobante"),
                            'cc.comcondescripcion', 'a.agennombre', 'c.cajanumero')
                            ->join('agencia as a', 'a.agenid', '=', 'cc.agenid')
                            ->join('caja as c', 'c.cajaid', '=', 'cc.cajaid')
                            ->whereDate('cc.comconfechahora', $fechaComprobante)
                            ->where('cc.usuaid', $idUsuario)
                            ->where('cc.agenid', $agenciaId)
                            ->where('cc.cajaid', $cajaId)
                            ->first();


                            $nombreUsuario = 'Prueba';

                            $arrayDatos = [ 
                                "nombreUsuario"       => $nombreUsuario,
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

            return response()->json(['success' => true, 'message' => 'Proceso realizado con éxito', "dataComprobante" => $dataComprobante ]);
        } catch (Exception $error){

            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }

    }
}
