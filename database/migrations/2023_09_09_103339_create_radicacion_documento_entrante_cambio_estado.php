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
        Schema::create('radicaciondocentcambioestado', function (Blueprint $table) {
            $table->increments('radeceid')->unsigned()->comment('Identificador de la tabla cambio estado radicacion documento entrante');
            $table->integer('radoenid')->unsigned()->comment('Identificador del radicado del documento entrante');
            $table->tinyInteger('tierdeid')->unsigned()->comment('Identificador del tipo de estado radicación documento entrante');
            $table->smallInteger('radeceusuaid')->unsigned()->comment('Identificador del usuario que crea el estado del radicado');
            $table->dateTime('radecefechahora')->comment('Fecha y hora en la cual se crea el cambio estado del radicado');
            $table->string('radeceobservacion', 500)->nullable()->comment('Observación del cambio estado radicado');
            $table->timestamps();
            $table->foreign('radoenid')->references('radoenid')->on('radicaciondocumentoentrante')->onUpdate('cascade')->index('fk_radoenradece'); 
            $table->foreign('tierdeid')->references('tierdeid')->on('tipoestadoraddocentrante')->onUpdate('cascade')->index('fk_tierderadece'); 
            $table->foreign('radeceusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaradece'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radicaciondocentcambioestado');
    }
};
