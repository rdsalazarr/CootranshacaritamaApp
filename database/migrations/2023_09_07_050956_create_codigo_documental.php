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
        Schema::create('codigodocumental', function (Blueprint $table) {
            $table->bigIncrements('coddocid')->unsigned()->comment('Identificador de la tabla codigo documental');
            $table->smallInteger('depeid')->unsigned()->comment('Identificador de la dependencia');
            $table->smallInteger('serdocid')->unsigned()->comment('Identificador de la serie documental');
            $table->mediumInteger('susedoid')->unsigned()->comment('Identificador de la sub serie');
            $table->tinyInteger('tipdocid')->unsigned()->comment('Identificador de la tipo documento');
            $table->tinyInteger('tipmedid')->unsigned()->comment('Identificador del tipo de medio');
            $table->tinyInteger('tiptraid')->unsigned()->comment('Identificador del tipo de trámite');
            $table->tinyInteger('tipdetid')->unsigned()->comment('Identificador del tipo de destino');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->date('coddocfechahora')->comment('Fecha y hora actual en el que se crea el documento');
            $table->timestamps();
            $table->foreign('depeid')->references('depeid')->on('dependencia')->onUpdate('cascade')->index('fk_depecoddoc');
            $table->foreign('serdocid')->references('serdocid')->on('seriedocumental')->onUpdate('cascade')->index('fk_serdoccoddoc'); 
            $table->foreign('susedoid')->references('susedoid')->on('subseriedocumental')->onUpdate('cascade')->index('fk_susedocoddoc'); 
            $table->foreign('tipdocid')->references('tipdocid')->on('tipodocumental')->onUpdate('cascade')->index('fk_tipdoccoddoc'); 
            $table->foreign('tipmedid')->references('tipmedid')->on('tipomedio')->onUpdate('cascade')->index('fk_tipmedcoddoc'); 
            $table->foreign('tiptraid')->references('tiptraid')->on('tipotramite')->onUpdate('cascade')->index('fk_tiptracoddoc'); 
            $table->foreign('tipdetid')->references('tipdetid')->on('tipodestino')->onUpdate('cascade')->index('fk_tipdepcoddoc'); 
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_coddocuser'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigodocumental');
    }
};
