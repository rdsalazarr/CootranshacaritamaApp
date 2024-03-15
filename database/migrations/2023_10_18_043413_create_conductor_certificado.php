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
        Schema::create('conductorcertificado', function (Blueprint $table) {
            $table->bigIncrements('concerid')->unsigned()->comment('Identificador de la tabla conductor certificado');
            $table->integer('condid')->unsigned()->comment('Identificador del conductor');
            $table->string('concerextension', 5)->nullable()->comment('Extensión del certifcado del conductor');
            $table->string('concernombrearchivooriginal', 200)->nullable()->comment('Nombre con el cual se ha subido el certifcado del conductor'); 
            $table->string('concernombrearchivoeditado', 200)->nullable()->comment('Nombre editado con el cual se ha subido el certifcado del conductor');
            $table->string('concerrutaarchivo', 500)->nullable()->comment('Ruta enfuscada del certifcado del conductor'); 
            $table->timestamps();
            $table->foreign('condid')->references('condid')->on('conductor')->onUpdate('cascade')->index('fk_condconcer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductorcertificado');
    }
};
