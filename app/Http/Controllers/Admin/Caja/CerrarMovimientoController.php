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

     


        SELECT SUM(ccd.cocodemonto) FROM comprobantecontabledetalle as ccd
        INNER JOIN cuentacontable as cc on cc.cueconid = ccd.cueconid
        INNER JOIN comprobantecontable as cct on cct.cueconid = ccd.comconid
        INNER JOIN movimientocaja as mc on mc.movcajid = cct.movcajid
        WHERE mc.usuaid = '' AND mc.cajaid = '' AND mc.movcajfechahoraapertura = ''
        


        */

        //DB::raw('(SELECT SUM(encovalorenvio) AS encovalorenvio FROM encomienda WHERE plarutid = pr.plarutid) AS valorEnvio'),

        $data = DB::table('cuentacontable')->select('cueconid','cueconnombre','cueconnaturaleza','cueconcodigo','cueconactiva',
									DB::raw("if(cueconnaturaleza = 'C' ,'Crédito', 'Debito') as naturaleza"),
                                    DB::raw("if(cueconactiva = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('cueconid')->get();

        return response()->json(["data" => $data]);
    }
  
}