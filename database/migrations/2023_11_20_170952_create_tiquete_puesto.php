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
        Schema::create('tiquetepuesto', function (Blueprint $table) {
            $table->bigIncrements('tiqpueid')->unsigned()->comment('Identificador de la tabla tiquete puesto');
            $table->integer('tiquid')->unsigned()->comment('Identificador del tiquete');
            $table->string('tiqpuenumeropuesto', 3)->comment('Número de puesto en el tiquete');
            $table->timestamps();
            $table->unique(['tiquid','tiqpuenumeropuesto'],'uk_tiquetepuesto');
            $table->foreign('tiquid')->references('tiquid')->on('tiquete')->onUpdate('cascade')->index('fk_tiqutiqpue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiquetepuesto');
    }
};
