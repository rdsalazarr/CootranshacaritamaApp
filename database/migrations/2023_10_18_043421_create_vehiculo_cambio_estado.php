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
        Schema::create('vehiculocambioestado', function (Blueprint $table) {
            $table->increments('vecaesid')->unsigned()->comment('Identificador de la tabla vehículo cambio estado');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->string('tiesveid', 2)->unsigned()->comment('Identificador del tipo de estado vehículo');
            $table->smallInteger('vecaesusuaid')->unsigned()->comment('Identificador del usuario que crea el estado del vehículo');
            $table->dateTime('vecaesfechahora')->comment('Fecha y hora en la cual se crea el cambio estado del vehículo');
            $table->string('vecaesobservacion', 500)->nullable()->comment('Observación del cambio estado del vehículo');
            $table->timestamps();
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehivecaes'); 
            $table->foreign('tiesveid')->references('tiesveid')->on('tipoestadovehiculo')->onUpdate('cascade')->index('fk_tiesvevecaes'); 
            $table->foreign('vecaesusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuavecaes'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculocambioestado');
    }
};
