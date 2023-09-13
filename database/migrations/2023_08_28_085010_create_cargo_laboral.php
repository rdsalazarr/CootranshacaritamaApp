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
        Schema::create('cargolaboral', function (Blueprint $table) {
            $table->smallIncrements('carlabid')->index('pk_carlab')->comment('Identificador de la tabla cargo laboral');
            $table->string('carlabnombre', 100)->comment('Nombre del cargo laboral');
            $table->boolean('carlabactivo')->default(true)->comment('Determina si el cargo laboral');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargolaboral');
    }
};
