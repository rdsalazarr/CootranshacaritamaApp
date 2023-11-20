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
            $table->string('timovecuotasotenimiento', 9)->comment('Cuota de sotenimiento del tipo de modalidad del vehículo');
            $table->string('timovedescuentopagoanticipado', 4)->comment('Descuento por pago anual anticipado de la cuota de sostenimiento de administración del tipo de modalidad del vehículo');
            $table->string('timoverecargomora', 4)->comment('Recargo de mora de la cuota de sostenimiento de administración del tipo de modalidad del vehículo');
            $table->boolean('timovetienedespacho')->default(true)->comment('Determina si el tipo modalidad del vehículo tiene despacho');
            $table->primary('timoveid')->index('pk_timove');
            $table->timestamps();
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
