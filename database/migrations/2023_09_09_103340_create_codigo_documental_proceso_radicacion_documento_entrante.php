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
        Schema::create('coddocumprocesoraddocentrante', function (Blueprint $table) {
            $table->bigIncrements('cdprdeid')->unsigned()->comment('Identificador de la tabla codigo documental proceso radicado documento entrante');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->integer('radoenid')->unsigned()->comment('Identificador del radicado del documento entrante');
            $table->timestamps();
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcdprde');
            $table->foreign('radoenid')->references('radoenid')->on('radicaciondocumentoentrante')->onUpdate('cascade')->index('fk_radoencdprde');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesoraddocentrante');
    }
};
