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
        Schema::create('tipoconductor', function (Blueprint $table) {
            $table->string('tipconid', 2)->comment('Identificador de la tabla tipo conductor');
            $table->string('tipconnombre', 30)->comment('Nombre del tipo de conductor');
            $table->primary('tipconid')->index('pk_tipcon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoconductor');
    }
};
