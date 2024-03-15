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
        Schema::create('solicitud', function (Blueprint $table) {
            $table->increments('soliid')->unsigned()->comment('Identificador de la tabla solicitud');
            $table->integer('peradoid')->unsigned()->comment('Identificador de la persona que radica el documento');
            $table->integer('radoenid')->unsigned()->nullable()->comment('Identificador del radicado del documento entrante');
            $table->string('tipsolid', 2)->comment('Identificador del tipo de solicitud');
            $table->string('timesoid', 2)->comment('Identificador del tipo medio solicitud');
            $table->integer('vehiid')->unsigned()->nullable()->comment('Identificador del vehículo');
            $table->integer('condid')->unsigned()->nullable()->comment('Identificador del conductor');
            $table->dateTime('solifechahoraregistro')->comment('Fecha y hora en la cual se registra');
            $table->dateTime('solifechahoraincidente')->nullable()->comment('Fecha y hora en la cual se presento el incidente');
            $table->string('solimotivo', 2000)->comment('Motivo que contiene la solicitud');
            $table->string('soliobservacion', 1000)->nullable()->comment('Observaciones a la solicitud');
            $table->boolean('soliradicado')->default(false)->comment('Determina si la solicitud tiene radicado');
            $table->timestamps();
            $table->foreign('radoenid')->references('radoenid')->on('radicaciondocumentoentrante')->onUpdate('cascade')->index('fk_radoensoli');
            $table->foreign('peradoid')->references('peradoid')->on('personaradicadocumento')->onUpdate('cascade')->index('fk_peradosoli');
            $table->foreign('tipsolid')->references('tipsolid')->on('tiposolicitud')->onUpdate('cascade')->index('fk_tipsolsoli');
            $table->foreign('timesoid')->references('timesoid')->on('tipomediosolicitud')->onUpdate('cascade')->index('fk_timesosoli');
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehisoli');
            $table->foreign('condid')->references('condid')->on('conductor')->onUpdate('cascade')->index('fk_condsoli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud');
    }
};
