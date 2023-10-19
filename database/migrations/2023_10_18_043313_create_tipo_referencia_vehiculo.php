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
        Schema::create('tiporeferenciavehiculo', function (Blueprint $table) {
            $table->smallIncrements('tireveid')->unsigned()->comment('Identificador de la tabla tipo referencia vehículo');
            $table->string('tirevenombre', 50)->comment('Nombre del tipo vehículo');
            $table->boolean('tireveactivo')->default(true)->comment('Determina si el tipo de referencia del vehículo se encuentra activo');      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiporeferenciavehiculo');
    }
};
