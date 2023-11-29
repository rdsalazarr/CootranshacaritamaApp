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
        Schema::create('configuracionencomienda', function (Blueprint $table) {
            $table->tinyIncrements('conencid')->comment('Identificador de la tabla configuración encomienda'); 
            $table->decimal('conencvalorminimoenvio', 10, 0)->comment('Valor mínimo del envío de la encomienda');
            $table->decimal('conencporcentajeseguro', 3, 0)->comment('Porcentaje del seguro del envío de la encomienda');
            $table->decimal('conencporcencomisionempresa', 3, 0)->comment('Porcentaje de comisión de la empresa del envío de la encomienda');
            $table->decimal('conencporcencomisionagencia', 3, 0)->comment('Porcentaje de comisión de la agencia del envío de la encomienda');
            $table->decimal('conencporcencomisionvehiculo', 3, 0)->comment('Porcentaje de comisión del vehículo del envío de la encomienda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracionencomienda');
    }
};
