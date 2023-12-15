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
        Schema::create('vehiculocontrato', function (Blueprint $table) {
            $table->increments('vehconid')->unsigned()->comment('Identificador de la tabla vehículo contrato');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->integer('asocid')->unsigned()->comment('Identificador del asociado');
            $table->integer('persidgerente')->unsigned()->comment('Identificador de la persona que es gerente de la empresa');
            $table->year('vehconanio', 4)->comment('Año en el cual se realiza el contrato del vehículo');
            $table->string('vehconnumero', 4)->comment('Número de contrato del vehículo por cada año');
            $table->date('vehconfechainicial')->comment('Fecha inicial del contrato del vehículo');
            $table->date('vehconfechafinal')->comment('Fecha final del contrato del vehículo');
            $table->string('vehconobservacion', 500)->nullable()->comment('Observaciones realizada al contrato del vehículo');
            $table->timestamps();
            $table->unique(['vehconanio','vehconnumero'],'uk_vehiculocontrato');
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehivehcon');
            $table->foreign('asocid')->references('asocid')->on('asociado')->onUpdate('cascade')->index('fk_asocvehcon');
            $table->foreign('persidgerente')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_persvehcon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculocontrato');
    }
};
