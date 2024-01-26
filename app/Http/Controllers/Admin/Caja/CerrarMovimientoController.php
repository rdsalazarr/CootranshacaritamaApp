<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
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

        $movimientosContables = DB::table('cuentacontable as cc')
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
                                        AND DATE(mc1.movcajfechahoraapertura) =  '$fechaActual'
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
                                        AND DATE(mc1.movcajfechahoraapertura) =  '$fechaActual'
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

        return response()->json(['success' => true, "data" => $movimientosContables, "movimientoCaja" => $movimientoCaja, "cajaAbierta" => $cajaAbierta, "nombreUsuario" => $nombreUsuario]);
    }
  
}