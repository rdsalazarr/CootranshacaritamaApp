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
        Schema::create('tipoestadoasociado', function (Blueprint $table) {
            $table->string('tiesasid', 2)->comment('Identificador del tipo de estado del asociado');
            $table->string('tiesasnombre', 30)->comment('Nombre del tipo de estado del asociado');
            $table->primary('tiesasid')->index('pk_tiesas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestadoasociado');
    }
};
