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
        Schema::create('radicaciondocumentoentrante', function (Blueprint $table) {
            $table->increments('radoenid')->unsigned()->comment('Identificador de la tabla radicacion documento entrante');
            $table->integer('peradoid')->unsigned()->comment('Identificador de la persona que radica el documento');
            $table->tinyInteger('tipmedid')->unsigned()->comment('Identificador del tipo de medio');
            $table->tinyInteger('tierdeid')->unsigned()->comment('Identificador del tipo de estado radicación documento entrante');
            $table->tinyInteger('depaid')->unsigned()->nullable()->comment('Identificador del departamento del cual proviene el documento');
            $table->smallInteger('muniid')->unsigned()->nullable()->comment('Identificador del municipio del cual proviene el documento');
            $table->smallInteger('depeid')->unsigned()->comment('Identificador de la dependencia'); 
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el estado del documento');
            $table->string('radoenconsecutivo', 4)->comment('Consecutivo del radicado');
            $table->string('radoenanio', 4)->comment('Año en el cual se crea el radicado');
            $table->dateTime('radoenfechahoraradicado')->comment('Fecha y hora en la cual se radica el documento');
            $table->date('radoenfechadocumento')->comment('Fecha que contiene el documento');
            $table->date('radoenfechallegada')->comment('Fecha de llegada del documento');
            $table->string('radoenpersonaentregadocumento', 100)->comment('Nombre de la persona que radica el documento');
            $table->string('radoenasunto', 500)->comment('Asunto que contiene el documento para radicar');
            $table->boolean('radoentieneanexo')->default(false)->comment('Determina si el radicado tiene anexo');
            $table->string('radoendescripcionanexo', 300)->nullable()->comment('Descripción del anexo');
            $table->boolean('radoentienecopia')->default(false)->comment('Determina si el radicado tiene copia');
            $table->string('radoenobservacion', 300)->nullable()->comment('Observación general del radicado del documento');
            $table->timestamps();
            $table->unique(['radoenconsecutivo','radoenanio'],'uk_radicaciondocumentoentrante');
            $table->foreign('peradoid')->references('peradoid')->on('personaradicadocumento')->onUpdate('cascade')->index('fk_peradoradoen');
            $table->foreign('tipmedid')->references('tipmedid')->on('tipomedio')->onUpdate('cascade')->index('fk_tipmedradoen');
            $table->foreign('tierdeid')->references('tierdeid')->on('tipoestadoraddocentrante')->onUpdate('cascade')->index('fk_tierderadoen');
            $table->foreign('depaid')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_deparadoen');
            $table->foreign('muniid')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_muniradoen');
            $table->foreign('depeid')->references('depeid')->on('dependencia')->onUpdate('cascade')->index('fk_deperadoen');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_radoen');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radicaciondocumentoentrante');
    }
};
