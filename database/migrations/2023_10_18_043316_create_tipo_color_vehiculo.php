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
        Schema::create('tipocolorvehiculo', function (Blueprint $table) {
            $table->smallIncrements('ticoveid')->unsigned()->comment('Identificador de la tabla tipo color vehículo');
            $table->string('ticovenombre', 50)->comment('Nombre del color del tipo vehículo');
            $table->boolean('ticoveactivo')->default(true)->comment('Determina si el tipo del color del vehículo se encuentra activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipocolorvehiculo');
    }
};
