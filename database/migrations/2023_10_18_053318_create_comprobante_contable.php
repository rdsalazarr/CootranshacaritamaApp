<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }

    /*comcontipoestado = Activo => A, Cerrado => C, Anulado => X*/

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobante_contable');
    }
};
