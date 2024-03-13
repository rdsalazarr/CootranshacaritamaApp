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
        Schema::create('personaserviciofidelizacion', function (Blueprint $table) {
            $table->bigIncrements('pesefiid')->unsigned()->comment('Identificador de la tabla persona servicio fidelizacion');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia que está generando el registro de la fidelización');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el registro de la fidelización');          
            $table->integer('perserid')->unsigned()->comment('Identificador de la persona que utiliza el servicio de la fidelización');
            $table->dateTime('pesefifechahoraregistro')->comment('Fecha y hora actual en el que se registra la fidelización');
            $table->string('pesefitipoproceso', 1)->default('T')->comment('Tipo de proceso de la fidelización (Tiquete, Encomienda)');
            $table->decimal('pesefinumeropunto', 6, 0)->comment('Número de punto obtenido en la fidelización');
            $table->dateTime('pesefifechahoraredimido')->nullable()->comment('Fecha y hora actual en el que se redime la fidelización');
            $table->boolean('pesefiredimido')->default(false)->comment('Determina si los puntos han sido redimido'); 
            $table->timestamps();
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agenpesefi');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuapesefi');         
            $table->foreign('perserid')->references('perserid')->on('personaservicio')->onUpdate('cascade')->index('fk_perserpesefi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personaserviciofidelizacion');
    }
};
