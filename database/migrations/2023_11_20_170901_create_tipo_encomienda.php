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
        Schema::create('tipoencomienda', function (Blueprint $table) {
            $table->string('tipencid', 2)->comment('Identificador de la tabla tipo encomienda');
            $table->string('tipencnombre', 30)->comment('Nombre del tipo encomienda');
            $table->primary('tipencid')->index('pk_tipenc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoencomienda');
    }
};
