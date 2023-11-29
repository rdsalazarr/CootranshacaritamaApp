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
        Schema::create('coddocumprocesocertificado', function (Blueprint $table) {
            $table->bigIncrements('codopcid')->unsigned()->comment('Identificador de la tabla codigo documental proceso certificado');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->tinyInteger('tipedoid')->unsigned()->comment('Identificador del tipo de persona documental');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el documento');
            $table->string('codopcconsecutivo', 4)->comment('Consecutivo de la certificado');
            $table->string('codopcsigla', 3)->comment('Sigla de la dependencia productora de la certificado');
            $table->year('codopcanio', 4)->comment('Año en el cual se crea la certificado');  
            $table->string('codopctitulo', 200)->comment('Título con el que se crea la certificado');
            $table->string('codopccontenidoinicial', 1000)->comment('contenido incial de la certificado');
            $table->timestamps();
            $table->unique(['codopcconsecutivo','codopcsigla','codopcanio'],'uk_coddocumprocesocertificado');
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopc'); 
            $table->foreign('tipedoid')->references('tipedoid')->on('tipopersonadocumental')->onUpdate('cascade')->index('fk_tippedocodopc');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodopc');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesocertificado');
    }
};
