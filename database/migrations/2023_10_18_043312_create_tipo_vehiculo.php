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
        Schema::create('tipovehiculo', function (Blueprint $table) {
            $table->smallIncrements('tipvehid')->unsigned()->comment('Identificador de la tabla tipo vehículo');
            $table->string('tipvehnombre', 50)->comment('Nombre del tipo vehículo');
            $table->string('tipvehreferencia', 30)->nullable()->comment('Referencia del tipo vehículo');
            $table->tinyInteger('tipvecapacidad')->unsigned()->default(0)->comment('Capacidad del tipo de vehículo');
            $table->tinyInteger('tipvenumerofilas')->unsigned()->default(0)->comment('Número de filas del tipo de vehículo');
            $table->tinyInteger('tipvenumerocolumnas')->unsigned()->default(0)->comment('Número de columnas del tipo de vehículo');
            $table->boolean('tipvehactivo')->default(true)->comment('Determina si el tipo vehículo se encuentra activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipovehiculo');
    }
};
