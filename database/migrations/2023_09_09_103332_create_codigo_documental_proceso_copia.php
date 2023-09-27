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
        Schema::create('coddocumprocesocopia', function (Blueprint $table) {
            $table->bigIncrements('codoppid')->unsigned()->comment('Identificador de la tabla codigo documental proceso copia');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->smallInteger('depeid')->unsigned()->nullable()->comment('Identificador de la dependencia');
            $table->boolean('codoppescopiadocumento')->default(false)->comment('Determina si es una copia en el documento');
            $table->datetime('codoppfechacompartido')->nullable()->comment('Fecha y hora en la cual se comparte el documento');
            $table->datetime('codoppfechaleido')->nullable()->comment('Fecha y hora en la cual se lee el documento'); 
            $table->timestamps();            
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopp'); 
            $table->foreign('depeid')->references('depeid')->on('dependencia')->onUpdate('cascade')->index('fk_depecodopp'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesocopia');
    }
};
