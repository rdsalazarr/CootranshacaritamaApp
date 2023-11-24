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
        Schema::create('tipoestadoencomienda', function (Blueprint $table) {
            $table->string('tiesenid', 2)->comment('Identificador de la tabla tipo estado encomienda');
            $table->string('tiesennombre', 30)->comment('Nombre del tipo de estado de la encomienda');
            $table->primary('tiesenid')->index('pk_tiesen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestadoencomienda');
    }
};
