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
        Schema::create('tipoestantearchivador', function (Blueprint $table) {
            $table->smallIncrements('tiesarid')->unsigned()->comment('Identificador de la tabla tipo estante archivador');
            $table->string('tiesarnombre', 50)->comment('Nombre del tipo estante archivador');
            $table->boolean('tiesaractivo')->default(true)->comment('Determina si el estante archivador se encuentra activo'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestantearchivador');
    }
};
