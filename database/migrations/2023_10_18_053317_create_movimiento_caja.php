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
        Schema::create('movimientocaja', function (Blueprint $table) {
            $table->bigIncrements('movcajid')->unsigned()->comment('Identificador de la tabla movimiento caja');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->tinyInteger('cajaid')->unsigned()->comment('Identificador de la caja');
            $table->dateTime('movcajfechahoraapertura')->comment('Fecha y hora en la cual se abre la caja');
            $table->decimal('movcajsaldoinicial', 10, 2)->comment('Saldo incial para abrir la caja');
            $table->dateTime('movcajfechahoracierre')->nullable()->comment('Fecha y hora en la cual se cierra la caja');
            $table->decimal('movcajsaldofinal', 10, 2)->nullable()->comment('Saldo final con el que cierra la caja');
            $table->boolean('movcajcerradoautomaticamente')->default(false)->comment('Determina si la el movimeinto de la caja fue cerrada automaticamente');
            $table->timestamps();
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuamovcaj');
            $table->foreign('cajaid')->references('cajaid')->on('caja')->onUpdate('cascade')->index('fk_cajamovcaj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientocaja');
    }
};
