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
        Schema::create('vehiculocontratoasociado', function (Blueprint $table) {
            $table->bigIncrements('vecoasid')->unsigned()->comment('Identificador de la tabla vehículo contrato asociado');
            $table->integer('vehconid')->unsigned()->comment('Identificador del contrato del vehículo');
            $table->integer('asocid')->unsigned()->comment('Identificador del asociado');
            $table->timestamps();
            $table->foreign('vehconid')->references('vehconid')->on('vehiculocontrato')->onUpdate('cascade')->index('fk_vehconvecoas');
            $table->foreign('asocid')->references('asocid')->on('asociado')->onUpdate('cascade')->index('fk_asocvecoas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculocontratoasociado');
    }
};
