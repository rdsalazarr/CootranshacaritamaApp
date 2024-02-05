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
            $table->decimal('lincretasanominal',6,2)->comment('Tasa nominal para línea de crédito'); 
            $table->string('lincremontominimo', 10)->comment('Monto mínimo de la línea de crédito');
            $table->string('lincremontomaximo', 10)->comment('Monto máximo de la línea de crédito');
            $table->string('lincreplazomaximo', 3)->default(1)->comment('Plazo máximo en meses de la línea de crédito');
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
