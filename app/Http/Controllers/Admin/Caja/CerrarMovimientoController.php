<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Http\Controllers\Controller;
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

/*

SELECT FORMAT(SUM(ccd.cocodemonto), 0)
FROM comprobantecontabledetalle as ccd
INNER JOIN cuentacontable as cc ON cc.cueconid = ccd.cueconid
INNER JOIN comprobantecontable as cct ON cct.comconid = ccd.comconid
INNER JOIN movimientocaja as mc ON mc.movcajid = cct.movcajid
WHERE cc.cueconnaturaleza ='D'
and mc.usuaid = '2'
AND mc.cajaid = '1'
AND cct.agenid = '101'
AND DATE(mc.movcajfechahoraapertura) = '2024-01-25';


SELECT SUM(ccd.cocodemonto)
FROM comprobantecontabledetalle as ccd
INNER JOIN cuentacontable as cc ON cc.cueconid = ccd.cueconid
INNER JOIN comprobantecontable as cct ON cct.comconid = ccd.comconid
INNER JOIN movimientocaja as mc ON mc.movcajid = cct.movcajid
WHERE cc.cueconnaturaleza ='D'
and mc.usuaid = '2'
AND mc.cajaid = '1'
AND cct.agenid = '101'
AND DATE(mc.movcajfechahoraapertura) = '2024-01-25';

*/



        /* Schema::create('cuentacontable', function (Blueprint $table) {
            $table->increments('cueconid')->unsigned()->comment('Identificador de la tabla cuenta contable');
            $table->string('cueconcodigo', 20)->comment('Codigo contable de la cuenta contable');
            $table->string('cueconnombre', 200)->comment('Nombre de la cuenta contable');
            $table->string('cueconnaturaleza', 1)->default('D')->comment('Naturaleza de la cuenta contable');
            $table->boolean('cueconactiva')->default(true)->comment('Determina si la cuenta contable se encuentra activa');
            $table->timestamps();
        });
        
         Schema::create('comprobantecontabledetalle', function (Blueprint $table) {
            $table->bigIncrements('cocodeid')->unsigned()->comment('Identificador de la tabla movimiento caja detallado');
            $table->bigInteger('comconid')->unsigned()->comment('Identificador del comprobante contable');
            $table->integer('cueconid')->unsigned()->comment('Identificador de la cuenta contable');
            $table->dateTime('cocodefechahora')->comment('Fecha y hora en la cual se realiza el registro');
            $table->decimal('cocodemonto', 12, 2)->nullable()->comment('Monto del movimiento de caja detallado');
            $table->boolean('cocodecontabilizado')->default(false)->comment('Determina si el movimiento fue contabilizado');
            $table->timestamps();
            $table->foreign('comconid')->references('comconid')->on('comprobantecontable')->onUpdate('cascade')->index('fk_comconcocode');
            $table->foreign('cueconid')->references('cueconid')->on('cuentacontable')->onUpdate('cascade')->index('fk_cueconcocode');
        });

      Schema::create('comprobantecontable', function (Blueprint $table) {
            $table->bigIncrements('comconid')->unsigned()->comment('Identificador de la tabla comprobante contable');
            $table->bigInteger('movcajid')->unsigned()->comment('Identificador del movimiento caja');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia');
            $table->tinyInteger('cajaid')->unsigned()->comment('Identificador de la caja');
            $table->year('comconanio', 4)->comment('Año en el cual se registra el comprobante contable');
            $table->string('comconconsecutivo', 5)->comment('Consecutivo del comprobante contable asignado por cada año');
            $table->dateTime('comconfechahora')->comment('Fecha y hora en la cual se crea el comprobante contable');
            $table->string('comcondescripcion', 1000)->comment('Descripción del comprobante contable');
            $table->dateTime('comconfechahoracierre')->nullable()->comment('Fecha y hora en la cual se cierra el comprobante contable');
            $table->string('comconestado', 1)->default('A')->comment('Estado del comprobante contable');
            $table->timestamps();
            $table->unique(['agenid','comconanio','comconconsecutivo'],'uk_comprobantecontable');
            $table->foreign('movcajid')->references('movcajid')->on('movimientocaja')->onUpdate('cascade')->index('fk_movcajcomcon');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacomcon');
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agencomcon');
            $table->foreign('cajaid')->references('cajaid')->on('caja')->onUpdate('cascade')->index('fk_cajacomcon');            
        });

        Schema::create('movimientocaja', function (Blueprint $table) {
            $table->bigIncrements('movcajid')->unsigned()->comment('Identificador de la tabla movimiento caja');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->tinyInteger('cajaid')->unsigned()->comment('Identificador de la caja');
            $table->dateTime('movcajfechahoraapertura')->comment('Fecha y hora en la cual se abre la caja');
            $table->decimal('movcajsaldoinicial', 10, 2)->comment('Saldo incial para abrir la caja');
            $table->dateTime('movcajfechahoracierre')->nullable()->comment('Fecha y hora en la cual se cierra la caja');
            $table->decimal('movcajsaldofinal', 10, 2)->nullable()->comment('Saldo final con el que cierra la caja');
            $table->boolean('movcajcerradoautomaticamente')->default(false)->comment('Determina si la el movimeinto de la caja fue cerrada automaticamente');
            $table->timestamps();
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuamovcaj');
            $table->foreign('cajaid')->references('cajaid')->on('caja')->onUpdate('cascade')->index('fk_cajamovcaj');
        });  

        */

        //DB::raw('(SELECT SUM(encovalorenvio) AS encovalorenvio FROM encomienda WHERE plarutid = pr.plarutid) AS valorDebito'),

        //'cc.cueconnaturaleza',
        /* and mc.usuaid = '$idUsuario'
                                    AND mc.cajaid = '$cajaId'
                                    AND cct.agenid = '$idUsuario'
                                    AND cc1.cueconid = cc.cueconid
                                    AND DATE(mc.movcajfechahoraapertura) = '$fechaActual'*/

        /*$movimientosContables = DB::table('cuentacontable as cc')->select('cc.cueconid','cc.cueconnombre','cc.cueconcodigo',        
                                    DB::raw("(SELECT FORMAT(SUM(ccd.cocodemonto), 0)
                                            FROM comprobantecontabledetalle as ccd
                                            INNER JOIN cuentacontable as cc1 ON cc1.cueconid = ccd.cueconid
                                            INNER JOIN comprobantecontable as cct1 ON cct1.comconid = ccd.comconid
                                            INNER JOIN movimientocaja as mc1 ON mc1.movcajid = cct1.movcajid
                                            WHERE cc1.cueconnaturaleza = 'D'
                                            AND cc1.cueconid = cc.cueconid
                                            AND mc1.cajaid = mc.cajaid
                                            AND cct1.agenid = cct.agenid
                                            AND mc1.usuaid = mc.usuaid
                                            AND DATE(mc1.movcajfechahoraapertura) = DATE(mc.movcajfechahoraapertura)
                                        ) AS valorDebito"),
                                        DB::raw("(SELECT FORMAT(SUM(ccd.cocodemonto), 0)
                                            FROM comprobantecontabledetalle as ccd
                                            INNER JOIN cuentacontable as cc1 ON cc1.cueconid = ccd.cueconid
                                            INNER JOIN comprobantecontable as cct1 ON cct1.comconid = ccd.comconid
                                            INNER JOIN movimientocaja as mc1 ON mc1.movcajid = cct.movcajid
                                            WHERE cc1.cueconnaturaleza = 'C'
                                            AND cc1.cueconid = cc.cueconid
                                            AND mc1.cajaid = mc.cajaid
                                            AND cct1.agenid = cct.agenid
                                            AND mc1.usuaid = mc.usuaid
                                            AND DATE(mc1.movcajfechahoraapertura) = DATE(mc.movcajfechahoraapertura)
                                        ) AS valorCredito") )
                                    ->join('comprobantecontabledetalle as mcd', 'mcd.cueconid', '=', 'cc.cueconid')
                                    ->join('comprobantecontable as cct', 'cct.comconid', '=', 'mcd.comconid')
                                    ->join('movimientocaja as mc', function($join)
                                    {
                                        $join->on('mc.movcajid', '=', 'cct.movcajid');
                                        $join->on('mc.usuaid', '=', 'cct.usuaid');
                                    })
                                    ->whereDate('mc.movcajfechahoraapertura', $fechaActual)
                                    ->where('mc.usuaid', $idUsuario)
                                    ->where('cct.agenid', $agenciaId)
                                    ->where('mc.cajaid', $cajaId)
                                    ->groupBy('cc.cueconid', 'cc.cueconnombre', 'cc.cueconcodigo')
                                    ->orderBy('cc.cueconid')->get();*/

                                    //->join('comprobantecontable as cc', 'cc.movcajid', '=', 'mc.movcajid')


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

        return response()->json(["data" => $movimientosContables, "movimientoCaja" => $movimientoCaja]);
    }
  
}