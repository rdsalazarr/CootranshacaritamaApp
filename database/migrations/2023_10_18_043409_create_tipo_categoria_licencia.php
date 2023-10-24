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
        Schema::create('tipocategorialicencia', function (Blueprint $table) {
            $table->string('ticaliid', 2)->comment('Identificador del tipo de categoría de licencia');
            $table->string('ticalinombre', 30)->comment('Nombre del tipo del categoría de licencia');
            $table->primary('ticaliid')->index('pk_ticali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipocategorialicencia');
    }
};
