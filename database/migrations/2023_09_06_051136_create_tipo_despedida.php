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
        Schema::create('tipodespedida', function (Blueprint $table) {
            $table->smallIncrements('tipdesid')->unsigned()->comment('Identificador de la tabla tipo despedida');
            $table->string('tipdesnombre', 100)->comment('Nombre del tipo despedida');
            $table->boolean('tipdesactivo')->default(true)->comment('Determina si el tipo de despedida se encuentra activo'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipodespedida');
    }
};
