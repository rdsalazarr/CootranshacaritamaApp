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
        Schema::create('tipopersonadocumental', function (Blueprint $table) {
            $table->tinyIncrements('tipedoid')->index('pk_tipedo')->unsigned()->comment('Identificador del tipo de persona documental');
            $table->string('tipedonombre', 80)->comment('Nombre del tipo de persona documental');
            $table->boolean('tipedoactivo')->default(true)->comment('Determina si el tipo de persona documental se encuentra activo'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipopersonadocumental');
    }
};
