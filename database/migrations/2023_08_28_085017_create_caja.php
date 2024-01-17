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
        Schema::create('caja', function (Blueprint $table) {
            $table->tinyInteger('cajaid')->unsigned()->comment('Identificador de la tabla caja');
            $table->string('cajanumero', 30)->comment('Nombre o número de la caja');
            $table->primary('cajaid')->index('pk_caja');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja');
    }
};
