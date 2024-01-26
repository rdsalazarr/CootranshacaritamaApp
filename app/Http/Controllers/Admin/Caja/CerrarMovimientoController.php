<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use Carbon\Carbon;

class CerrarMovimientoController extends Controller
{
    public function index()
    {
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
                                            WHERE cc.cueconnaturaleza ='D'
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
                                            WHERE cc.cueconnaturaleza ='C'
                                            AND mc.usuaid = '$idUsuario'
                                            AND mc.cajaid = '$cajaId'
                                            AND cct.agenid = '$agenciaId'
                                            AND DATE(mc.movcajfechahoraapertura) = '$fechaActual'
                                        ) AS valorCredito") )
                                    ->join('comprobantecontable as cc', 'cc.movcajid', '=', 'mc.movcajid')
                                    ->whereDate('movcajfechahoraapertura', $fechaActual)
                                    ->where('mc.usuaid', $idUsuario)
                                    ->where('cc.agenid', $agenciaId)
                                    ->where('mc.cajaid', $cajaId)->first();

        return response()->json(['success'    => true,         "data"          => $this->obtenerMovimientosContables(), "movimientoCaja" => $movimientoCaja,
                                "cajaAbierta" => $cajaAbierta, "nombreUsuario" => $nombreUsuario  ]);
    }

    public function salve(Request $request)
	{
        $this->validate(request(),['idValor'    => 'required|numeric|between:1,999999999']);

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
            $dataFactura = $generarPdf->generarComprobanteContable($arrayDatos, $this->obtenerMovimientosContablesPdf());

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Proceso realizado con éxito', "dataFactura" => $dataFactura ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function obtenerMovimientosContables()
    {
        //Contiene el mismo contenido de obtenerMovimientosContablesPdf solo que en esa no se puede formatear los valores
        $fechaActual     = Carbon::now()->format('Y-m-d');
        $idUsuario       = Auth::id();
        $agenciaId       = auth()->user()->agenid;
        $cajaId          = auth()->user()->cajaid;

        return DB::table('cuentacontable as cc')
                    ->select(DB::raw('DATE(ccd.cocodefechahora) as fechaMovimiento'), 'cc.cueconid','cc.cueconnombre', 'cc.cueconcodigo','mc.cajaid', 'cct.agenid', 'mc.usuaid',
                        DB::raw("CONCAT('$ ', (SELECT COALESCE(FORMAT(SUM(ccd.cocodemonto), 0), 0)
                            FROM comprobantecontabledetalle as ccd
                            INNER JOIN cuentacontable as cc1 ON cc1.cueconid = ccd.cueconid
                            INNER JOIN comprobantecontable as cct1 ON cct1.comconid = ccd.comconid
                            INNER JOIN movimientocaja as mc1 ON mc1.movcajid = cct1.movcajid
                            WHERE cc1.cueconnaturaleza = 'D'
                            AND cc1.cueconid = cc.cueconid
                            AND mc1.cajaid = mc.cajaid
                            AND cct1.agenid = cct.agenid
                            AND mc1.usuaid = mc.usuaid
                            AND DATE(mc1.movcajfechahoraapertura) = '$fechaActual'
                        )) AS valorDebito"),
                        DB::raw("CONCAT('$ ', (SELECT COALESCE(FORMAT(SUM(ccd.cocodemonto), 0), 0)
                            FROM comprobantecontabledetalle as ccd
                            INNER JOIN cuentacontable as cc1 ON cc1.cueconid = ccd.cueconid
                            INNER JOIN comprobantecontable as cct1 ON cct1.comconid = ccd.comconid
                            INNER JOIN movimientocaja as mc1 ON mc1.movcajid = cct1.movcajid
                            WHERE cc1.cueconnaturaleza = 'C'
                            AND cc1.cueconid = cc.cueconid
                            AND mc1.cajaid = mc.cajaid
                            AND cct1.agenid = cct.agenid
                            AND mc1.usuaid = mc.usuaid
                            AND DATE(mc1.movcajfechahoraapertura) = '$fechaActual'
                        )) AS valorCredito")
                    )
                    ->join('comprobantecontabledetalle as ccd', 'ccd.cueconid', '=', 'cc.cueconid')
                    ->join('comprobantecontable as cct', 'cct.comconid', '=', 'ccd.comconid')
                    ->join('movimientocaja as mc', function ($join) {
                        $join->on('mc.movcajid', '=', 'cct.movcajid');
                        $join->on('mc.usuaid', '=', 'cct.usuaid');
                    })
                    ->whereDate('mc.movcajfechahoraapertura', $fechaActual)
                    ->where('mc.usuaid', $idUsuario)
                    ->where('cct.agenid', $agenciaId)
                    ->where('mc.cajaid', $cajaId)
                    ->groupBy(DB::raw('DATE(ccd.cocodefechahora)'), 'cc.cueconid', 'cc.cueconnombre', 'cc.cueconcodigo', 'mc.cajaid', 'cct.agenid', 'mc.usuaid')
                    ->orderBy('cc.cueconid')
                    ->get();
    }

    public function obtenerMovimientosContablesPdf()
    {
        $fechaActual     = Carbon::now()->format('Y-m-d');
        $idUsuario       = Auth::id();
        $agenciaId       = auth()->user()->agenid;
        $cajaId          = auth()->user()->cajaid;

        return DB::table('cuentacontable as cc')
                    ->select(DB::raw('DATE(ccd.cocodefechahora) as fechaMovimiento'), 'cc.cueconid','cc.cueconnombre', 'cc.cueconcodigo','mc.cajaid', 'cct.agenid', 'mc.usuaid',
                        DB::raw("(SELECT COALESCE(SUM(ccd.cocodemonto), 0)
                            FROM comprobantecontabledetalle as ccd
                            INNER JOIN cuentacontable as cc1 ON cc1.cueconid = ccd.cueconid
                            INNER JOIN comprobantecontable as cct1 ON cct1.comconid = ccd.comconid
                            INNER JOIN movimientocaja as mc1 ON mc1.movcajid = cct1.movcajid
                            WHERE cc1.cueconnaturaleza = 'D'
                            AND cc1.cueconid = cc.cueconid
                            AND mc1.cajaid = mc.cajaid
                            AND cct1.agenid = cct.agenid
                            AND mc1.usuaid = mc.usuaid
                            AND DATE(mc1.movcajfechahoraapertura) = '$fechaActual'
                        ) AS valorDebito"),
                        DB::raw("(SELECT COALESCE(SUM(ccd.cocodemonto), 0)
                            FROM comprobantecontabledetalle as ccd
                            INNER JOIN cuentacontable as cc1 ON cc1.cueconid = ccd.cueconid
                            INNER JOIN comprobantecontable as cct1 ON cct1.comconid = ccd.comconid
                            INNER JOIN movimientocaja as mc1 ON mc1.movcajid = cct1.movcajid
                            WHERE cc1.cueconnaturaleza = 'C'
                            AND cc1.cueconid = cc.cueconid
                            AND mc1.cajaid = mc.cajaid
                            AND cct1.agenid = cct.agenid
                            AND mc1.usuaid = mc.usuaid
                            AND DATE(mc1.movcajfechahoraapertura) = '$fechaActual'
                        ) AS valorCredito")
                    )
                    ->join('comprobantecontabledetalle as ccd', 'ccd.cueconid', '=', 'cc.cueconid')
                    ->join('comprobantecontable as cct', 'cct.comconid', '=', 'ccd.comconid')
                    ->join('movimientocaja as mc', function ($join) {
                        $join->on('mc.movcajid', '=', 'cct.movcajid');
                        $join->on('mc.usuaid', '=', 'cct.usuaid');
                    })
                    ->whereDate('mc.movcajfechahoraapertura', $fechaActual)
                    ->where('mc.usuaid', $idUsuario)
                    ->where('cct.agenid', $agenciaId)
                    ->where('mc.cajaid', $cajaId)
                    ->groupBy(DB::raw('DATE(ccd.cocodefechahora)'), 'cc.cueconid', 'cc.cueconnombre', 'cc.cueconcodigo', 'mc.cajaid', 'cct.agenid', 'mc.usuaid')
                    ->orderBy('cc.cueconid')
                    ->get();
    }
}