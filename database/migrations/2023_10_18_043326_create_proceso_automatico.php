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
        Schema::create('procesoautomatico', function (Blueprint $table) {
            $table->smallIncrements('proautid')->unsigned()->comment('Identificador de la tabla proceso automático');
            $table->string('proautnombre', 50)->comment('Nombre del proceso automático');
            $table->string('proautclasephp', 50)->comment('Nombre de la clase del proceso automático en PHP');
            $table->string('proautclasephp', 50)->comment('Nombre de la clase del proceso automático en PHP');
            $table->string('proautmetodo', 50)->comment('Método en PHP de la clase del proceso automático');
            $table->date('proautfechaejecucion')->comment('Fecha de ejecución del proceso automático');
            $table->string('proauttipo', 1)->default('D')->comment('Tipo de proceso dia o noche');           
            $table->timestamps();
            $table->unique(['proautnombre'],'uk_procesoautomatico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procesoautomatico');
    }
};