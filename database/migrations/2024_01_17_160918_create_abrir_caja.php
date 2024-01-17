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
        Schema::create('abrircaja', function (Blueprint $table) {
            $table->bigIncrements('abrcajid')->unsigned()->comment('Identificador de la tabla abrir caja');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->tinyInteger('cajaid')->unsigned()->comment('Identificador de la caja');
            $table->dateTime('abrcajfechahoraapertura')->comment('Fecha y hora en la cual se abre la caja');
            $table->decimal('abrcajsaldoinicial', 10, 0)->comment('Saldo incial para abrir la caja');
            $table->dateTime('abrcajfechahoracierre')->nullable()->comment('Fecha y hora en la cual se cierra la caja');
            $table->decimal('abrcajsaldofinal', 10, 0)->nullable()->comment('Saldo final con el que cierra la caja');
            $table->boolean('abrcajcerradaautomaticamente')->default(false)->comment('Determina si la caja fue cerrar automaticamente');
            $table->timestamps();
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaabrcaj');
            $table->foreign('cajaid')->references('cajaid')->on('caja')->onUpdate('cascade')->index('fk_cajaabrcaj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abrircaja');
    }
};
