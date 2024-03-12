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
            $table->tinyInteger('tiqudepaidorigen')->unsigned()->comment('Identificador del departamento de origen del tiquete');
            $table->smallInteger('tiqumuniidorigen')->unsigned()->comment('Identificador del municipio de origen del tiquete');
            $table->tinyInteger('tiqudepaiddestino')->unsigned()->comment('Identificador del departamento de destino del tiquete');
            $table->smallInteger('tiqumuniiddestino')->unsigned()->comment('Identificador del municipio de destino del tiquete');
            $table->year('tiquanio', 4)->comment('Año en el cual se registra el tiquete');
            $table->string('tiquconsecutivo', 5)->comment('Consecutivo del tiquete asignado por cada año');
            $table->dateTime('tiqufechahoraregistro')->comment('Fecha y hora actual en el que se registra el tiquete');
            $table->decimal('tiqucantidad', 4, 0)->comment('Cantidad de puesto en el tiquete');
            $table->decimal('tiquvalortiquete', 10, 0)->comment('Valor del tiquete');
            $table->decimal('tiquvalordescuento', 10, 0)->nullable()->comment('Valor de descuento del tiquete');
            $table->decimal('tiquvalorseguro', 6, 0)->nullable()->comment('Valor del seguro para el tiquete');
            $table->decimal('tiquvalorestampilla', 6, 0)->nullable()->comment('Valor de la estampilla del tiquete');
            $table->decimal('tiquvalorfondoreposicion', 6, 0)->comment('Valor del fondo de reposición del tiquete');
            $table->decimal('tiquvalorfondorecaudo', 6, 0)->comment('Valor del fondo de recaudo del tiquete');            
            $table->decimal('tiquvalortotal', 10, 0)->comment('Valor total del tiquete');
            $table->boolean('tiqucontabilizado')->default(false)->comment('Determina si el tiquete ha sido contabilizado'); 
            $table->timestamps();
            $table->unique(['agenid','tiquanio','tiquconsecutivo'],'uk_tiquete');
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agentiqu');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuatiqu');
            $table->foreign('plarutid')->references('plarutid')->on('planillaruta')->onUpdate('cascade')->index('fk_plaruttiqu');
            $table->foreign('perserid')->references('perserid')->on('personaservicio')->onUpdate('cascade')->index('fk_persertiqu');
            $table->foreign('tiqudepaidorigen')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depatiquorigen');
            $table->foreign('tiqumuniidorigen')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munitiquorigen');
            $table->foreign('tiqudepaiddestino')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depatiqudestino');
            $table->foreign('tiqumuniiddestino')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munitiqudestino');
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