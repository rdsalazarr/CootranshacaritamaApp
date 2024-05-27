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
        Schema::create('vehiculocontratofirma', function (Blueprint $table) {
            $table->bigIncrements('vecofiid')->unsigned()->comment('Identificador de la tabla vehiculo contrato firma');
            $table->integer('vehconid')->unsigned()->comment('Identificador de la tabla vehiculo contrato');
            $table->integer('persid')->unsigned()->comment('Identificador de la tabla persona');
            $table->string('vecofitoken', 20)->nullable()->comment('Token con el cual es firmado el contrato');
            $table->string('vecofiipacceso', 20)->nullable()->comment('Ip de la cual accede el usuario para realizar el proceso de la firma');
            $table->datetime('vecofifechahorafirmado')->nullable()->comment('Fecha y hora de la cual se firma el contrato');
            $table->datetime('vecofifechahoranotificacion')->nullable()->comment('Fecha y hora de la cual se envio la notifiación del token');
            $table->datetime('vecofifechahoramaxvalidez')->nullable()->comment('Fecha y hora maxima de validez del token'); 
            $table->string('vecofimensajecorreo', 500)->nullable()->comment('Contendio de la información enviada al correo');
            $table->string('vecofimensajecelular', 200)->nullable()->comment('Contendio de la información enviada al celular');
            $table->boolean('vecofifirmado')->default(false)->comment('Determina si el contrato esta firmado');
            $table->timestamps();
            $table->foreign('vehconid')->references('vehconid')->on('vehiculocontrato')->onUpdate('cascade')->index('fk_vehconvecofi'); 
            $table->foreign('persid')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_persvecofi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculocontratofirma');
    }
};
