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
        Schema::create('mensajeimpresion', function (Blueprint $table) {
            $table->tinyIncrements('menimpid')->comment('Identificador de la tabla mensaje impresión');
            $table->string('menimpnombre', 50)->comment('Nombre del mensaje de impresión');
            $table->string('menimpvalor', 500)->nullable()->comment('Valor del mensaje de impresión');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajeimpresion');
    }
};
