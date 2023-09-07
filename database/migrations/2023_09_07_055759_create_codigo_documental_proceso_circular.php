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
        Schema::create('coddocumprocesocircular', function (Blueprint $table) {
            $table->bigIncrements('codoplid')->unsigned()->comment('Identificador de la tabla codigo documental proceso circular');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el documento');
            $table->smallInteger('tipdesid')->unsigned()->comment('Identificador de la tabla tipo despedida');
            $table->string('codoplconsecutivo', 4)->comment('Consecutivo de la circular');
            $table->string('codoplsigla', 3)->comment('Sigla de la dependencia productora de la circular');
            $table->string('codoplanio', 4)->comment('Año en el cual se crea la circular'); 
            $table->timestamps();
            $table->unique(['codoplconsecutivo','codoplsigla','codoplanio'],'uk_coddocumprocesocircular');           
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopl'); 
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodopl');
            $table->foreign('tipdesid')->references('tipdesid')->on('tipodespedida')->onUpdate('cascade')->index('fk_tipdescodopl');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigo_documental_proceso_circular');
    }
};
