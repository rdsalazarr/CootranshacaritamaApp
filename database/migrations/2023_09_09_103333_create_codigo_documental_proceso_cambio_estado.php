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
        Schema::create('coddocumprocesocambioestado', function (Blueprint $table) {
            $table->bigIncrements('codpceid')->unsigned()->comment('Identificador de la tabla codigo documental proceso cambio estado');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->tinyInteger('tiesdoid')->unsigned()->comment('Identificador de la tabla tipo estado documento');
            $table->smallInteger('codpceusuaid')->unsigned()->comment('Identificador del usuario que crea el estado del documento');
            $table->dateTime('codpcefechahora')->comment('Fecha y hora en la cual se crea el cambio estado del documento');
            $table->string('codpceobservacion', 500)->nullable()->comment('Observación del cambio estado documento');
            $table->timestamps();
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodpce'); 
            $table->foreign('tiesdoid')->references('tiesdoid')->on('tipoestadodocumento')->onUpdate('cascade')->index('fk_tiesdocodpce'); 
            $table->foreign('codpceusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodpce');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesocambioestado');
    }
};
