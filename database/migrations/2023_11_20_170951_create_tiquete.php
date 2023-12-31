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
        Schema::create('tiquete', function (Blueprint $table) {
            $table->increments('tiquid')->unsigned()->comment('Identificador de la tabla tiquete');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia que esta generando el tiquete');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el registro del tiquete');
            $table->integer('plarutid')->unsigned()->comment('Identificador de la planilla ruta');
            $table->integer('perserid')->unsigned()->comment('Identificador de la persona que utiliza el servicio del tiquete');
            $table->tinyInteger('depaidorigen')->unsigned()->comment('Identificador del departamento de origen del tiquete');
            $table->smallInteger('muniidorigen')->unsigned()->comment('Identificador del municipio de origen del tiquete');
            $table->tinyInteger('depaiddestino')->unsigned()->comment('Identificador del departamento de destino del tiquete');
            $table->smallInteger('muniiddestino')->unsigned()->comment('Identificador del municipio de destino del tiquete');
            $table->year('tiquanio', 4)->comment('Año en el cual se registra el tiquete');
            $table->string('tiquconsecutivo', 5)->comment('Consecutivo del tiquete asignado por cada año');
            $table->dateTime('tiqufechahoraregistro')->comment('Fecha y hora actual en el que se registra el tiquete');
            $table->decimal('tiqucantidad', 4)->comment('Cantidad de puesto en el tiquete');
            $table->decimal('tiquvalortiquete', 10, 0)->comment('Valor del tiquete');
            $table->decimal('tiquvalordescuento', 10, 0)->comment('Valor de descuento del tiquete');
            $table->decimal('tiquvalorfondoreposicion', 10, 0)->comment('Valor del fondo de reposición del tiquete');
            $table->decimal('tiquvalortotal', 10, 0)->comment('Valor total del tiquete');
            $table->timestamps();
            $table->unique(['agenid','tiquanio','tiquconsecutivo'],'uk_tiquete');
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agentiqu');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuatiqu');
            $table->foreign('plarutid')->references('plarutid')->on('planillaruta')->onUpdate('cascade')->index('fk_plaruttiqu');
            $table->foreign('perserid')->references('perserid')->on('personaservicio')->onUpdate('cascade')->index('fk_persertiqu');
            $table->foreign('depaidorigen')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depatiquorigen');
            $table->foreign('muniidorigen')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munitiquorigen');
            $table->foreign('depaiddestino')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depatiqudestino');
            $table->foreign('muniiddestino')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munitiqudestino');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiquete');
    }
};