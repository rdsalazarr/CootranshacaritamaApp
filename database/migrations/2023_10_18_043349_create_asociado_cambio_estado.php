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
        Schema::create('asociadocambioestado', function (Blueprint $table) {
            $table->increments('ascaesid')->unsigned()->comment('Identificador de la tabla asociado cambio estado');
            $table->integer('asocid')->unsigned()->comment('Identificador de la tabla asociado');
            $table->string('tiesasid', 2)->unsigned()->comment('Identificador del tipo de estado asociado');
            $table->smallInteger('ascaesusuaid')->unsigned()->comment('Identificador del usuario que crea el estado del asociado');
            $table->dateTime('ascaesfechahora')->comment('Fecha y hora en la cual se crea el cambio estado del asociado');
            $table->string('ascaesobservacion', 500)->nullable()->comment('Observación del cambio estado del asociado');
            $table->timestamps();
            $table->foreign('asocid')->references('asocid')->on('asociado')->onUpdate('cascade')->index('fk_asocascaes'); 
            $table->foreign('tiesasid')->references('tiesasid')->on('tipoestadoasociado')->onUpdate('cascade')->index('fk_tiesasascaes'); 
            $table->foreign('ascaesusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaascaes'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asociadocambioestado');
    }
};
