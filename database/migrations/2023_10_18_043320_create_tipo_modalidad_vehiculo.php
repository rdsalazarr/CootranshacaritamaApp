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
        Schema::create('tipomodalidadvehiculo', function (Blueprint $table) {
            $table->string('timoveid', 2)->comment('Identificador del la tabla tipo modalidad vehículo');
            $table->string('timovenombre', 30)->comment('Nombre del tipo de modalidad del vehículo');
            $table->boolean('timovetienedespacho')->default(false)->comment('Determina si el tipo modalidad del vehículo tiene despacho');
            $table->primary('timoveid')->index('pk_timove');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipomodalidadvehiculo');
    }
};
