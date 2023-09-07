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
        Schema::create('tiposaludo', function (Blueprint $table) {
            $table->tinyIncrements('tipsalid')->unsigned()->comment('Identificador del tipo de saludo');
            $table->string('tipsalnombre', 100)->comment('Nombre del tipo de saludo');
            $table->boolean('tipsalactivo')->default(true)->comment('Determina si el tipo de saludo se encuentra activo'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiposaludo');
    }
};
