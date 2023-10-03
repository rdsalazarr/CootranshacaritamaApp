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
        Schema::create('tipodocumental', function (Blueprint $table) {
            $table->tinyIncrements('tipdocid')->unsigned()->comment('Identificador de la tabla tipo documental');
            $table->string('tipdoccodigo', 2)->unique('uk_tipodocumental')->comment('Código del tipo documental');
            $table->string('tipdocnombre', 50)->comment('Nombre del tipo documental');
            $table->boolean('tipdocproducedocumento')->default(false)->comment('Determina si el tipo documental produce documento');
            $table->boolean('tipdocactivo')->default(true)->comment('Determina si el tipo de documento se encuentra activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipodocumental');
    }
};
