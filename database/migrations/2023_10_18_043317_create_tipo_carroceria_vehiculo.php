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
        Schema::create('tipocarroceriavehiculo', function (Blueprint $table) {
            $table->smallIncrements('ticaveid')->unsigned()->comment('Identificador de la tabla tipo carroceria vehículo');
            $table->string('ticavenombre', 50)->comment('Nombre del tipo de carroceria del vehículo');
            $table->boolean('ticaveactivo')->default(true)->comment('Determina si el tipo del carroceria del vehículo se encuentra activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipocarroceriavehiculo');
    }
};
