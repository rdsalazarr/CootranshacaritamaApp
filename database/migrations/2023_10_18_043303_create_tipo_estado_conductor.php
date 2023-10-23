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
        Schema::create('tipoestadoconductor', function (Blueprint $table) {
            $table->string('tiescoid', 2)->comment('Identificador del tipo de estado del conductor');
            $table->string('tiesconombre', 30)->comment('Nombre del tipo de estado del conductor');
            $table->primary('tiescoid')->index('pk_tiesco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestadoconductor');
    }
};
