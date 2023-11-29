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
        Schema::create('coddocumprocesoconstancia', function (Blueprint $table) {
            $table->bigIncrements('codopnid')->unsigned()->comment('Identificador de la tabla codigo documental proceso constancia');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->tinyInteger('tipedoid')->unsigned()->comment('Identificador del tipo de persona documental');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el documento');
            $table->string('codopnconsecutivo', 4)->comment('Consecutivo de la constancia');
            $table->string('codopnsigla', 3)->comment('Sigla de la dependencia productora de la constancia');
            $table->year('codopnanio', 4)->comment('Año en el cual se crea la constancia');  
            $table->string('codopntitulo', 200)->comment('Título con el que se crea la constancia');
            $table->string('codopncontenidoinicial', 1000)->comment('contenido incial de la constancia');
            $table->timestamps();
            $table->unique(['codopnconsecutivo','codopnsigla','codopnanio'],'uk_coddocumprocesocontancia');
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopn'); 
            $table->foreign('tipedoid')->references('tipedoid')->on('tipopersonadocumental')->onUpdate('cascade')->index('fk_tippedocodopn');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodopn');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigo_documental_proceso_constancia');
    }
};
