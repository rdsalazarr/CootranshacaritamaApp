<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use App\Models\Despacho\Tiquete;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use Exception, Auth, DB;
use App\Util\notificar;
use Carbon\Carbon;

class CerrarMovimientoController extends Controller
{
    public function index()
    {
        try{
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');
            $idUsuario       = Auth::id();
            $agenciaId       = auth()->user()->agenid;
            $cajaId          = auth()->user()->cajaid;
            $nombreUsuario   = auth()->user()->usuanombre.' '.auth()->user()->usuaapellidos;

            $cajaAbierta = MovimientoCaja::verificarCajaAbierta();
            if(!$cajaAbierta){
                $message         = 'No es posible cerrar una caja sin un registro previo';
                return response()->json(['success' => false, 'message'=> $message, "nombreUsuario" => $nombreUsuario]);
            }

            $movimientoCaja = DB::table('movimientocaja as mc')
                                        ->select('mc.movcajsaldoinicial', DB::raw("FORMAT(mc.movcajsaldoinicial, 0) as saldoInicial"),
                                        DB::raw("(SELECT SUM(ccd.cocodemonto)
                                                FROM comprobantecontabledetalle as ccd
                                                INNER JOIN cuentacontable as cc ON cc.cueconid = ccd.cueconid
                                                INNER JOIN comprobantecontable as cct ON cct.comconid = ccd.comconid
                                                INNER JOIN movimientocaja as mc ON mc.movcajid = cct.movcajid
                                                WHERE cc.cueconnaturaleza = 'D'
                                                AND mc.usuaid = '$idUsuario'
                                                AND mc.cajaid = '$cajaId'
                                                AND cct.agenid = '$agenciaId'
                                                AND DATE(mc.movcajfechahoraapertura) = '$fechaActual'
                                            ) AS valorDebito"),
                                        DB::raw("(SELECT SUM(ccd.cocodemonto)
                                                FROM comprobantecontabledetalle as ccd
                                                INNER JOIN cuentacontable as cc ON cc.cueconid = ccd.cueconid
                                                INNER JOIN comprobantecontable as cct ON cct.comconid = ccd.comconid
                                                INNER JOIN movimientocaja as mc ON mc.movcajid = cct.movcajid
                                                WHERE cc.cueconnaturaleza = 'C'
                                                AND mc.usuaid = '$idUsuario'
                                                AND mc.cajaid = '$cajaId'
                                                AND cct.agenid = '$agenciaId'
                                                AND DATE(mc.movcajfechahoraapertura) = '$fechaActual'
                                            ) AS valorCredito"),
                                        DB::raw("(SELECT SUM(t.tiquvalortotal)
                                                    FROM tiquete as t
                                                    WHERE t.tiqucontabilizado = 0
                                                    AND t.usuaid = '$idUsuario'
                                                ) AS saldoTiquete") )
                                        ->join('comprobantecontable as cc', 'cc.movcajid', '=', 'mc.movcajid')
                                        ->whereDate('mc.movcajfechahoraapertura', $fechaActual)
                                        ->where('mc.usuaid', $idUsuario)
                                        ->where('cc.agenid', $agenciaId)
                                        ->where('mc.cajaid', $cajaId)->first();


            $data = MovimientoCaja::obtenerMovimientosContables($fechaActual, $idUsuario, $agenciaId, $cajaId);

            return response()->json(['success'    => true,         "data"          => $data,        "movimientoCaja" => $movimientoCaja,
                                    "cajaAbierta" => $cajaAbierta, "nombreUsuario" => $nombreUsuario  ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salve(Request $request)
	{
        $this->validate(request(),['idValor'  => 'required|numeric|between:1,999999999']);

        DB::beginTransaction();
        try {
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');
            $idUsuario       = Auth::id();
            $agenciaId       = auth()->user()->agenid;
            $cajaId          = auth()->user()->cajaid;
            $nombreUsuario   = auth()->user()->usuanombre.' '.auth()->user()->usuaapellidos;

            $comprobanteContableId = DB::table('comprobantecontable as cc')
                                        ->select('cc.comconid', 'cc.movcajid', DB::raw('DATE(cc.comconfechahora) as fechaComprobante'), 
                                        DB::raw("CONCAT(cc.comconanio, cc.comconconsecutivo) as numeroComprobante"),
                                        'cc.comcondescripcion', 'a.agennombre', 'c.cajanumero')
                                        ->join('agencia as a', 'a.agenid', '=', 'cc.agenid')
                                        ->join('caja as c', 'c.cajaid', '=', 'cc.cajaid')
                                        ->whereDate('cc.comconfechahora', $fechaActual)
                                        ->where('cc.usuaid', $idUsuario)
                                        ->where('cc.agenid', $agenciaId)
                                        ->where('cc.cajaid', $cajaId)
                                        ->first();

            $comprobantecontable                        = ComprobanteContable::findOrFail($comprobanteContableId->comconid);
            $comprobantecontable->comconfechahoracierre = $fechaHoraActual;
            $comprobantecontable->comconestado          = 'C';
            $comprobantecontable->save();

            $comprobanteContableDetalles = DB::table('comprobantecontabledetalle')->select('cocodeid')
                                            ->whereDate('comconid', $comprobanteContableId->comconid)
                                            ->get();

            foreach($comprobanteContableDetalles as $comprobanteContableDetalleId){
                $comprobantecontabledetalle                       = ComprobanteContableDetalle::findOrFail($comprobanteContableDetalleId->cocodeid);
                $comprobantecontabledetalle->cocodecontabilizado = true;
                $comprobantecontabledetalle->save();
            }

            $movimientocaja                        = MovimientoCaja::findOrFail($comprobanteContableId->movcajid);
            $movimientocaja->movcajfechahoracierre = $fechaHoraActual;
            $movimientocaja->movcajsaldofinal      = $request->idValor;
            $movimientocaja->save();

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
            $dataFactura = $generarPdf->generarComprobanteContable($arrayDatos, MovimientoCaja::obtenerMovimientosContablesPdf($fechaActual, $idUsuario, $agenciaId, $cajaId));

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Proceso realizado con éxito', "dataFactura" => $dataFactura ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function tiquete(Request $request)
	{ 
        DB::beginTransaction();
        try {        

            $tiquetes = DB::table('tiquete')
                                ->select('tiquid', 'tiquvalortotal')
                                ->where('usuaid', Auth::id())
                                ->where('tiqucontabilizado', 0)
                                ->get();

            $valorContabilizar = 0;
            $fechaHoraActual   = Carbon::now();
            $fechaActual       = $fechaHoraActual->format('Y-m-d');
            foreach($tiquetes as $tiqueteEstado){
                $valorContabilizar                      += $tiqueteEstado->tiquvalortotal;
                $tiqueteContabilizado                   = Tiquete::findOrFail($tiqueteEstado->tiquid); 
                $tiqueteContabilizado->tiqucontabilizado = true;
                $tiqueteContabilizado->save();
            }

            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 1;//Caja
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $valorContabilizar;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 10; //CXP PAGO TIQUETE
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $valorContabilizar;
            $comprobantecontabledetalle->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Proceso realizado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }    
}