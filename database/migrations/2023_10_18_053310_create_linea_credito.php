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
        Schema::create('lineacredito', function (Blueprint $table) {
            $table->increments('lincreid')->unsigned()->comment('Identificador de la tabla línea de crédito');
            $table->string('lincrenombre', 100)->comment('Nombre de la línea de crédito');
            $table->decimal('lincreporcentaje',6,2)->nullable()->comment('Porcentaje de interés para línea de crédito'); 
            $table->boolean('lincreactiva')->default(true)->comment('Determina si la línea de crédito se encuentra activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineacredito');
    }
};
