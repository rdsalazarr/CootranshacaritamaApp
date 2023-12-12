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
        Schema::create('tiposerviciovehiculo', function (Blueprint $table) {
            $table->string('tiseveid', 2)->comment('Identificador del tipo de servicio del vehículo');
            $table->string('tisevenombre', 30)->comment('Nombre del tipo de servicio del vehículo');
            $table->primary('tiseveid')->index('pk_tiseve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiposerviciovehiculo');
    }
};
