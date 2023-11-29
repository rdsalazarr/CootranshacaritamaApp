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
        Schema::create('planillaruta', function (Blueprint $table) {
            $table->increments('plarutid')->unsigned()->comment('Identificador de la tabla planilla ruta');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia que esta generando la planilla');
            $table->integer('rutaid')->unsigned()->comment('Identificador de la ruta');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->integer('condid')->unsigned()->comment('Identificador del conductor');
            $table->smallInteger('usuaidregistra')->unsigned()->comment('Identificador del usuario que rgistra la planilla');
            $table->smallInteger('usuaidrecibe')->unsigned()->nullable()->comment('Identificador del usuario que recibe la planilla');
            $table->dateTime('plarutfechahoraregistro')->comment('Fecha y hora actual en el que se registra la planilla');
            $table->string('plarutconsecutivo', 6)->comment('Consecutivo de la planilla de la ruta');
            $table->dateTime('plarutfechahorasalida')->nullable()->comment('Fecha y hora actual se entrega la planilla para la ruta');
            $table->dateTime('plarutfechahorarecibe')->nullable()->comment('Fecha y hora actual en que se recibe la planilla para la ruta');
            $table->boolean('plarutdespachada')->default(false)->comment('Determina si la ruta fue despachada');
            $table->timestamps();
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agenplarut');
            $table->foreign('rutaid')->references('rutaid')->on('ruta')->onUpdate('cascade')->index('fk_rutaplarut');
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehiplarut'); 
            $table->foreign('condid')->references('condid')->on('conductor')->onUpdate('cascade')->index('fk_condplarut');
            $table->foreign('usuaidregistra')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaplarutregistra');
            $table->foreign('usuaidrecibe')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaplarutrecibe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planillaruta');
    }
};