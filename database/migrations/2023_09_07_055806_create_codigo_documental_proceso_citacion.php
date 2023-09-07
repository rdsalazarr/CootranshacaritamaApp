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
        Schema::create('coddocumprocesocitacion', function (Blueprint $table) {
            $table->bigIncrements('codoptid')->unsigned()->comment('Identificador de la tabla codigo documental proceso citación');       
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el documento');
            $table->tinyInteger('tipactid')->unsigned()->comment('Identificador del tipo de acta');  
            $table->string('codoptconsecutivo', 4)->comment('Consecutivo del citación');
            $table->string('codoptsigla', 3)->comment('Sigla de la dependencia productora del citación');
            $table->string('codoptanio', 4)->comment('Año en el cual se crea el citación');
            $table->string('codopthora', 8)->comment('Hora de la citación');
            $table->string('codoptlugar', 80)->comment('Lugar donde se realiza el citación');
            $table->date('codoptfecharealizacion')->comment('Fecha para la conovocatoria del citación');
            $table->timestamps();
            $table->unique(['codoptconsecutivo','codoptsigla','codoptanio'],'uk_coddocumprocesocitacion');       
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopt');  
            $table->foreign('tipactid')->references('tipactid')->on('tipoacta')->onUpdate('cascade')->index('fk_tipactcodopt'); 
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodopt'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigo_documental_proceso_citacion');
    }
};
