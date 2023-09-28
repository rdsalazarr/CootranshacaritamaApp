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
        Schema::create('coddocumprocesocompartido', function (Blueprint $table) {
            $table->bigIncrements('codopdid')->unsigned()->comment('Identificador de la tabla codigo documental proceso compartido');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario al que se le comparte el documento');
            $table->datetime('codopdfechacompartido')->comment('Fecha y hora en la cual se comparte el documento');
            $table->datetime('codopdfechaleido')->nullable()->comment('Fecha y hora en la cual se lee el documento');
            $table->timestamps();
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopd'); 
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodopd'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesocompartido');
    }
};
