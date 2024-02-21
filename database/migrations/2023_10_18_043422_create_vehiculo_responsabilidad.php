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
        Schema::create('vehiculoresponsabilidad', function (Blueprint $table) {
            $table->bigIncrements('vehresid')->unsigned()->comment('Identificador de la tabla vehículo responsabilidad');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->smallInteger('agenid')->unsigned()->nullable()->comment('Identificador de la agencia a la que recibe el pago');
            $table->smallInteger('usuaid')->unsigned()->nullable()->comment('Identificador del usuario que recibe el pago');
            $table->date('vehresfechacompromiso')->comment('Fecha máxima en la cual se debe realizar el pago de la responsabilidad');
            $table->decimal('vehresvalorresponsabilidad', 8, 0)->comment('Valor de la responsabilidad o cuota del pago mensual');
            $table->date('vehresfechapagado')->nullable()->comment('Fecha en la cual se realiza el pago de la responsabilidad');
            $table->decimal('vehresdescuento', 8, 0)->nullable()->comment('Valor de escuento por pago anticipado en la responsabilidad pagado');
            $table->decimal('vehresinteresmora', 8, 0)->nullable()->comment('Valor de interés de mora en la responsabilidad pagado');
            $table->decimal('vehresvalorpagado', 8, 0)->nullable()->comment('Valor de la responsabilidad pagado');
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehivehres');
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agenvehres');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuavehres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculoresponsabilidad');
    }
};
