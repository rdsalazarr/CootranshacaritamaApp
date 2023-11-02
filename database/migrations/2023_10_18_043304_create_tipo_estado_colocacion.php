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
        Schema::create('tipoestadocolocacion', function (Blueprint $table) {
            $table->string('tiesclid', 2)->comment('Identificador de la tabla tipo estado solicitud colocación');
            $table->string('tiesclnombre', 30)->comment('Nombre del tipo de estado de la solicitud de colocación');
            $table->primary('tiesclid')->index('pk_tiescl');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestadocolocacion');
    }
};
