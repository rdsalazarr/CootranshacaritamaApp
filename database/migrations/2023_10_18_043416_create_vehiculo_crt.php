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
        Schema::create('vehiculocrt', function (Blueprint $table) {
            $table->smallIncrements('vehcrtid')->unsigned()->comment('Identificador de la tabla vehículo CRT');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->string('vehcrtnumero', 30)->comment('Número del CRT del vehículo');
            $table->date('vehcrtfechainicial')->comment('Fecha inicial del CRT del vehículo');
            $table->date('vehcrtfechafinal')->comment('Fecha final del CRT del vehículo');
            $table->string('vehcrtextension', 5)->nullable()->comment('Extensión del archivo que se anexa del CRT del vehículo');
            $table->string('vehcrtnombrearchivooriginal', 200)->nullable()->comment('Nombre con el cual se ha subido el archivo que se anexa del CRT del vehículo'); 
            $table->string('vehcrtnombrearchivoeditado', 200)->nullable()->comment('Nombre editado con el cual se ha subido el archivo que se anexa del CRT del vehículo');
            $table->string('vehcrtrutaarchivo', 500)->nullable()->comment('Ruta enfuscada del archivo que se anexa del CRT del vehículo');
            $table->timestamps();
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehivehcrt'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculo_crt');
    }
};
