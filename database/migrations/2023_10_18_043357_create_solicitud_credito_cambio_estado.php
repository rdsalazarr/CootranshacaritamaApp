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
        Schema::create('solicitudcreditocambioestado', function (Blueprint $table) {
            $table->increments('socrceid')->unsigned()->comment('Identificador de la tabla solicitud de credito cambio estado');
            $table->integer('solcreid')->unsigned()->comment('Identificador de la solicitud de crédito');
            $table->string('tiesscid', 2)->unsigned()->comment('Identificador del tipo de estado solicitud de crédito');
            $table->smallInteger('socrceusuaid')->unsigned()->comment('Identificador del usuario que crea el estado de la solicitud de crédito');
            $table->dateTime('socrcefechahora')->comment('Fecha y hora en la cual se crea el cambio estado de la solicitud de crédito');
            $table->string('socrceobservacion', 500)->nullable()->comment('Observación del cambio estado de la solicitud de crédito');
            $table->timestamps();
            $table->foreign('solcreid')->references('solcreid')->on('solicitudcredito')->onUpdate('cascade')->index('fk_solcresocrce'); 
            $table->foreign('tiesscid')->references('tiesscid')->on('lineacredito')->onUpdate('cascade')->index('fk_tiesscsocrce'); 
            $table->foreign('socrceusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuasocrce'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudcreditocambioestado');
    }
};
