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
        Schema::create('tipomediosolicitud', function (Blueprint $table) {
            $table->string('timesoid', 2)->comment('Identificador de la tabla tipo medio solicitud');
            $table->string('timesonombre', 30)->comment('Nombre del tipo medio solicitud');
            $table->primary('timesoid')->index('pk_timeso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipomediosolicitud');
    }
};
