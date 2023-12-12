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
        Schema::create('tiposancion', function (Blueprint $table) {
            $table->smallIncrements('tipsanid')->unsigned()->comment('Identificador de la tabla tipo sanción');
            $table->string('tipsannombre', 50)->comment('Nombre del tipo vehículo');
            $table->boolean('tipsanactivo')->default(true)->comment('Determina si el tipo de sanción se encuentra activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiposancion');
    }
};
