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
        Schema::create('tipopersona', function (Blueprint $table) {
            $table->string('tipperid', 2)->comment('Identificador de la tabla tipo de persona');
            $table->string('tippernombre', 30)->comment('Nombre del tipo de persona');
            $table->primary('tipperid')->index('pk_tipper');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipopersona');
    }
};
