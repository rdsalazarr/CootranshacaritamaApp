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
        Schema::create('vehiculopoliza', function (Blueprint $table) {
            $table->bigIncrements('vehpolid')->unsigned()->comment('Identificador de la tabla vehículo póliza');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->string('vehpolnumeropolizacontractual', 30)->comment('Número de póliza contractual del vehículo');
            $table->string('vehpolnumeropolizaextcontrac', 30)->comment('Número de póliza extra contractual del vehículo');
            $table->date('vehpolfechainicial')->comment('Fecha inicial de la póliza del vehículo');
            $table->date('vehpolfechafinal')->comment('Fecha final de la póliza  del vehículo');
            $table->string('vehpolextension', 5)->nullable()->comment('Extensión del archivo que se anexa de la póliza del vehículo');
            $table->string('vehpolnombrearchivooriginal', 200)->nullable()->comment('Nombre con el cual se ha subido el archivo que se anexa de la póliza del vehículo'); 
            $table->string('vehpolnombrearchivoeditado', 200)->nullable()->comment('Nombre editado con el cual se ha subido el archivo que se anexa de la póliza del vehículo');
            $table->string('vehpolrutaarchivo', 500)->nullable()->comment('Ruta enfuscada del archivo que se anexa de la póliza del vehículo');
            $table->timestamps();
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehivehpol'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculopoliza');
    }
};
