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
        Schema::create('conductorlicencia', function (Blueprint $table) {
            $table->increments('conlicid')->unsigned()->comment('Identificador de la tabla conductor licencia');
            $table->integer('condid')->unsigned()->comment('Identificador del conductor');
            $table->string('ticaliid', 2)->comment('Identificador del tipo de categoría de la licencia');
            $table->string('conlicnumero', 30)->comment('Número del licencia');
            $table->date('conlicfechaexpedicion')->comment('Fecha de expedición de la licencia');
            $table->date('conlicfechavencimiento')->comment('Fecha de vencimiento de la licencia');
            $table->string('conlicextension', 5)->nullable()->comment('Extensión del archivo que se anexa a la licencia');
            $table->string('conlicnombrearchivooriginal', 200)->nullable()->comment('Nombre con el cual se ha subido el archivo que se anexa a la licencia');
            $table->string('conlicnombrearchivoeditado', 200)->nullable()->comment('Nombre editado con el cual se ha subido el archivo que se anexa a la licencia');
            $table->string('conlicrutaarchivo', 500)->nullable()->comment('Ruta enfuscada del archivo que se anexa a la licencia');
            $table->timestamps();
            $table->foreign('condid')->references('condid')->on('conductor')->onUpdate('cascade')->index('fk_condconlic');
            $table->foreign('ticaliid')->references('ticaliid')->on('tipocategorialicencia')->onUpdate('cascade')->index('fk_ticaliconlic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductorlicencia');
    }
};
