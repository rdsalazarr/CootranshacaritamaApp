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
        Schema::create('coddocumprocesoradicado', function (Blueprint $table) {
            $table->bigIncrements('codpraid')->unsigned()->comment('Identificador de la tabla codigo documental proceso radicado');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->smallInteger('codprauserid')->unsigned()->comment('Identificador del usuario que radica el documento');         
            $table->string('codpraconsecutivo', 5)->comment('Consecutivo de la racicación del documento');
            $table->string('codpraanio', 4)->comment('Año en el cual se radica el documento'); 
            $table->datetime('codprafechahoraradicado')->comment('Fecha y hora en la cual se radica el documento');
            $table->timestamps();
            $table->unique(['codpraconsecutivo','codpraanio'],'uk_coddocumprocesoradicado');  
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodpra'); 
            $table->foreign('codprauserid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodpra'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesoradicado');
    }
};
