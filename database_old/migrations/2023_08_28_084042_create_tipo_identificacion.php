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
        Schema::create('tipoidentificacion', function (Blueprint $table) {
            $table->tinyInteger('tipideid')->unsigned()->comment('Identificador de la tabla tipo identificación');
            $table->string('tipidesigla', 4)->unique('uk_tipoidentificacion')->comment('Sigla del tipo de identificación');
            $table->string('tipidenombre', 50)->comment('Nombre del tipo de identificación');
            $table->primary('tipideid')->index('pk_tipide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoidentificacion');
    }
};
