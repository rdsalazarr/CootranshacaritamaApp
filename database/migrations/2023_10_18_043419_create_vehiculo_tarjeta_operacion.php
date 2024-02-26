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
        Schema::create('vehiculotarjetaoperacion', function (Blueprint $table) {
            $table->bigIncrements('vetaopid')->unsigned()->comment('Identificador de la tabla vehículo tarjeta operación');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->string('tiseveid', 2)->comment('Identificador del tipo de servicio del vehículo');
            $table->string('vetaopnumero', 30)->comment('Número de la tarjeta de operación del vehículo');
            $table->date('vetaopfechainicial')->comment('Fecha inicial de la tarjeta de operación del vehículo');
            $table->date('vetaopfechafinal')->comment('Fecha final de la tarjeta de operación del vehículo');
            $table->string('vetaopenteadministrativo', 2)->comment('Ente administrativo que emite la tarjeta de operación del vehículo');
            $table->string('vetaopradioaccion', 2)->comment('Radio de acción de la tarjeta de operación del vehículo');
            $table->string('vetaopextension', 5)->nullable()->comment('Extensión del archivo que se anexa a la tarjeta de operación del vehículo');
            $table->string('vetaopnombrearchivooriginal', 200)->nullable()->comment('Nombre con el cual se ha subido el archivo que se anexa a la tarjeta de operación del vehículo'); 
            $table->string('vetaopnombrearchivoeditado', 200)->nullable()->comment('Nombre editado con el cual se ha subido el archivo que se anexa a la tarjeta de operación del vehículo');
            $table->string('vetaoprutaarchivo', 500)->nullable()->comment('Ruta enfuscada del archivo que se anexa a la tarjeta de operación del vehículo');
            $table->timestamps();
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehivetaop'); 
            $table->foreign('tiseveid')->references('tiseveid')->on('tiposerviciovehiculo')->onUpdate('cascade')->index('fk_tisevevetaop'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculotarjetaoperacion');
    }
};
