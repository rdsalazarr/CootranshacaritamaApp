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
        Schema::create('radicaciondocentdependencia', function (Blueprint $table) {
            $table->increments('radoedid')->unsigned()->comment('Identificador de la tabla radicacion documento entrante dependencia');
            $table->integer('radoenid')->unsigned()->comment('Identificador del radicado del documento entrante');
            $table->smallInteger('depeid')->unsigned()->comment('Identificador de la dependencia');
            $table->smallInteger('radoedsuaid')->unsigned()->nullable()->comment('Identificador del usuario que recibe el documento radicado');
            $table->dateTime('radoedfechahorarecibido')->nullable()->comment('Fecha y hora en la cual se recibe el documento');
            $table->timestamps();
            $table->foreign('radoenid')->references('radoenid')->on('radicaciondocumentoentrante')->onUpdate('cascade')->index('fk_radoenradoed');
            $table->foreign('depeid')->references('depeid')->on('dependencia')->onUpdate('cascade')->index('fk_deperadoed');
            $table->foreign('radoedsuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaradoed'); 
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radicaciondocentdependencia');
    }
};
