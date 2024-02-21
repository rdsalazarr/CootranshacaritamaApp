<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB, Auth;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table      = 'movimientocaja';
    protected $primaryKey = 'movcajid';
    protected $fillable   = ['usuaid','cajaid','movcajfechahoraapertura','movcajsaldoinicial','movcajfechahoracierre',
                            'movcajsaldofinal', 'movcajcerradaautomaticamente'];

    public static function verificarCajaAbierta()
    {
        $movimientocaja  = DB::table('movimientocaja')->select('movcajsaldofinal')
                                    ->whereDate('movcajfechahoraapertura', Carbon::now()->format('Y-m-d'))
                                    ->whereNull('movcajsaldofinal')
                                    ->where('usuaid', Auth::id())
                                    ->where('cajaid', auth()->user()->cajaid)
                                    ->first();

        return ($movimientocaja) ? true : false;
    }

    public static function obtenerMovimientosContables($fechaActual, $idUsuario, $agenciaId, $cajaId)
    {
        //Contiene el mismo contenido de obtenerMovimientosContablesPdf solo que en esa no se puede formatear los valores
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

    public static function obtenerMovimientosContablesPdf($fechaActual, $idUsuario, $agenciaId, $cajaId)
    {      
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