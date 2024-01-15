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
            $table->tinyInteger('tipvehcapacidad')->unsigned()->default(0)->comment('Capacidad del tipo de vehículo');
            $table->tinyInteger('tipvehnumerofilas')->unsigned()->default(0)->comment('Número de filas del tipo de vehículo');
            $table->tinyInteger('tipvehnumerocolumnas')->unsigned()->default(0)->comment('Número de columnas del tipo de vehículo');
            $table->string('tipvehclasecss', 50)->default('distribucionPuestoGeneral')->comment('Clase en CSS para poder visualizar el vehículo con su puesto');            
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
