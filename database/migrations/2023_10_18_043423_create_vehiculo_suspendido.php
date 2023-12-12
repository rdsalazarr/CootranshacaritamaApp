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
        Schema::create('vehiculosuspendido', function (Blueprint $table) {
            $table->bigIncrements('vehsusid')->unsigned()->comment('Identificador de la tabla vehículo responsabilidad');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que suspende el vehículo');
            $table->dateTime('vehsusfechahora')->comment('Fecha y hora en la cual se crea el registro de la suspención del vehículo');
            $table->date('vehsusfechainicialsuspencion')->comment('Fecha inicial de la suspención del vehículo');
            $table->date('vehsusfechafinalsuspencion')->comment('Fecha inicial de la suspención del vehículo');
            $table->string('vehsusmotivo', 500)->comment('Motivo de la suspención del vehículo');
            $table->boolean('vehsusprocesada')->default(false)->comment('Determina si la supención del vehículo ha sido procesada'); 
            $table->timestamps();
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehivehsus');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuavehsus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculosuspendido');
    }
};
