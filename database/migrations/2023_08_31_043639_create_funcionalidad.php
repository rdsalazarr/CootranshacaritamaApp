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
        Schema::create('funcionalidad', function (Blueprint $table) {
            $table->smallIncrements('funcid')->unsigned()->comment('Identificador de la tabla funcionalidad');
            $table->smallInteger('moduid')->unsigned()->comment('Identificador del módulo');
            $table->string('funcnombre', 80)->comment('Nombre de la funcionalidad');
            $table->string('functitulo', 80)->nullable()->comment('Título de la funcionalidad');
            $table->string('funcruta', 60)->nullable()->comment('Ruta de la funcionalidad');
            $table->string('funcicono', 30)->nullable()->comment('Clase de css para montar en el link del menú');
            $table->smallInteger('funcorden')->comment('Orden del en el árbol del menú');
            $table->boolean('funcactiva')->default(true)->comment('Determina si la funcionalidad encuentra activa'); 
            $table->timestamps();
            $table->foreign('moduid')->references('moduid')->on('modulo')->onUpdate('cascade')->index('fk_modufunc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionalidad');
    }
};
