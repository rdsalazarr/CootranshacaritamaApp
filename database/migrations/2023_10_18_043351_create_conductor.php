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
        Schema::create('conductor', function (Blueprint $table) {
            $table->increments('condid')->unsigned()->comment('Identificador de la tabla conductor');
            $table->integer('persid')->unsigned()->comment('Identificador de la persona');
            $table->string('tiescoid', 2)->comment('Identificador del tipo de estado del conductor');
            $table->date('condfechaingreso')->comment('Fecha de ingreso del conductor a la cooperativa');
            $table->timestamps();
            $table->foreign('persid')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_perscond');
            $table->foreign('tiescoid')->references('tiescoid')->on('tipoestadoconductor')->onUpdate('cascade')->index('fk_tiescocond');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductor');
    }
};
