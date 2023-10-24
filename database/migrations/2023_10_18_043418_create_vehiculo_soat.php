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
        Schema::create('vehiculosoat', function (Blueprint $table) {
            $table->smallIncrements('vehsoaid')->unsigned()->comment('Identificador de la tabla vehículo SOAT');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->string('vehsoanumero', 30)->comment('Número del SOAT del vehículo');
            $table->date('vehsoafechainicial')->comment('Fecha inicial del SOAT del vehículo');
            $table->date('vehsoafechafinal')->comment('Fecha final del SOAT del vehículo');
            $table->string('vehsoaextension', 5)->nullable()->comment('Extensión del archivo que se anexa del SOAT del vehículo');
            $table->string('vehsoanombrearchivooriginal', 200)->nullable()->comment('Nombre con el cual se ha subido el archivo que se anexa del SOAT del vehículo'); 
            $table->string('vehsoanombrearchivoeditado', 200)->nullable()->comment('Nombre editado con el cual se ha subido el archivo que se anexa del SOAT del vehículo');
            $table->string('vehsoarutaarchivo', 500)->nullable()->comment('Ruta enfuscada del archivo que se anexa del SOAT del vehículo');
            $table->timestamps();
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehivehsoa'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculosoat');
    }
};
