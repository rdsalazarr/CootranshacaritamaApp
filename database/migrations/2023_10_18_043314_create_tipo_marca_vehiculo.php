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
        Schema::create('tipomarcavehiculo', function (Blueprint $table) {
            $table->smallIncrements('timaveid')->unsigned()->comment('Identificador de la tabla tipo marca vehículo');
            $table->string('timavenombre', 50)->comment('Nombre de la marca del tipo vehículo');
            $table->boolean('timaveactiva')->default(true)->comment('Determina si el tipo de marcha del vehículo se encuentra activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipomarcavehiculo');
    }
};