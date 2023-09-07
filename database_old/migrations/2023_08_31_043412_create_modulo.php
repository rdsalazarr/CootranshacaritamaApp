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
        Schema::create('modulo', function (Blueprint $table) {
            $table->smallIncrements('moduid')->unsigned()->comment('Identificador de la tabla módulo');
            $table->string('modunombre', 30)->comment('Nombre del módulo');
            $table->string('moduicono', 30)->nullable()->comment('Clase de css para montar en el link del módulo');
            $table->smallInteger('moduorden')->comment('Orden del en el árbol del menú que se muesra el módulo');
            $table->boolean('moduactivo')->default(true)->comment('Determina si el módulo encuentra activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulo');
    }
};
