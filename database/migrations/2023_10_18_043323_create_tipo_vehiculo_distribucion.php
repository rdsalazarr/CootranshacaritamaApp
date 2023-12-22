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
        Schema::create('tipovehiculodistribucion', function (Blueprint $table) {
            $table->bigIncrements('tivediid')->unsigned()->comment('Identificador de la tabla tipo vehículo distribución');
            $table->smallInteger('tipvehid')->unsigned()->comment('Identificador del tipo de vehículo');
            $table->decimal('tivedicolumna', 3, 0)->comment('Columna de distribución de tipo de vehículo');
            $table->decimal('tivedifila', 3, 0)->comment('Fila de distribución de tipo de vehículo');
            $table->decimal('tivedipuesto', 3, 0)->comment('Contenido del número de ubicación del tipo de vehículo');
            $table->timestamps();
            $table->foreign('tipvehid')->references('tipvehid')->on('tipovehiculo')->onUpdate('cascade')->index('fk_tipvehtivedi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipovehiculodistribucion');
    }
};
