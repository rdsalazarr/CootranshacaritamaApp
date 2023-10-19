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
        Schema::create('agencia', function (Blueprint $table) {
            $table->smallIncrements('agenid')->unsigned()->comment('Identificador de la tabla agencia');
            $table->integer('persidresponsable')->unsigned()->comment('Identificador de la persona');
            $table->tinyInteger('agendepaid')->unsigned()->comment('Identificador del departamento');
            $table->smallInteger('agenmuniid')->nullable()->unsigned()->comment('Identificador del municipio');
            $table->string('agennombre', 100)->comment('Nombre del la agencia');
            $table->string('agendireccion', 100)->comment('Dirección de la agencia');
            $table->string('agencorreo', 80)->nullable()->comment('Correo de la agencia');
            $table->string('agentelefonocelular', 20)->nullable()->comment('Teléfono celular de la agencia');
            $table->string('agentelefonofijo', 20)->nullable()->comment('Teléfono fijo de la agencia');
            $table->boolean('agenactiva')->default(true)->comment('Determina si la agencia se encuentra activa');
            $table->timestamps();
            $table->foreign('agendepaid')->references('depaid')->on('departamento')->index('fk_depaagen');
            $table->foreign('agenmuniid')->references('muniid')->on('municipio')->index('fk_muniagen');
            $table->foreign('persidresponsable')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_persagen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencia');
    }
};
