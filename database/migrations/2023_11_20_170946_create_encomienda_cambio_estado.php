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
        Schema::create('encomiendacambioestado', function (Blueprint $table) {
            $table->bigIncrements('encaesid')->unsigned()->comment('Identificador de la tabla encomienda cambio estado');
            $table->integer('encoid')->unsigned()->comment('Identificador de la encomienda');
            $table->string('tiesenid', 2)->comment('Identificador del tipo de estado encomienda');
            $table->smallInteger('encaesusuaid')->unsigned()->comment('Identificador del usuario que crea el estado de la encomienda');
            $table->dateTime('encaesfechahora')->comment('Fecha y hora en la cual se crea el cambio estado de la encomienda');
            $table->string('encaesobservacion', 500)->nullable()->comment('Observación del cambio estado de la encomienda');
            $table->timestamps();
            $table->foreign('encoid')->references('encoid')->on('encomienda')->onUpdate('cascade')->index('fk_encoencaes');
            $table->foreign('tiesenid')->references('tiesenid')->on('tipoestadoencomienda')->onUpdate('cascade')->index('fk_tiesenencaes'); 
            $table->foreign('encaesusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaencaes'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encomiendacambioestado');
    }
};
