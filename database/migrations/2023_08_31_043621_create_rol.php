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
        Schema::create('rol', function (Blueprint $table) {
            $table->smallIncrements('rolid')->unsigned()->comment('Identificador de la tabla rol');
            $table->string('rolnombre', 80)->comment('Nombre del rol');
            $table->boolean('rolactivo')->default(true)->comment('Determina si el rol se encuentra activo'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol');
    }
};
