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
        Schema::create('contratoservicioespecialcond', function (Blueprint $table) {
            $table->bigIncrements('coseecod')->unsigned()->comment('Identificador de la tabla contrato servicio especial vehículo');
            $table->integer('coseesid')->unsigned()->comment('Identificador del contrato servicio especial');
            $table->integer('condid')->unsigned()->comment('Identificador del conductor');
            $table->timestamps();
            $table->foreign('coseesid')->references('coseesid')->on('contratoservicioespecial')->onUpdate('cascade')->index('fk_coseescoseeco');
            $table->foreign('condid')->references('condid')->on('conductor')->onUpdate('cascade')->index('fk_condcoseeco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratoservicioespecialcond');
    }
};