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
        Schema::create('tipomedio', function (Blueprint $table) {
            $table->tinyInteger('tipmedid')->unsigned()->comment('Identificador de la tabla tipo de medio');
            $table->string('tipmednombre', 20)->comment('Nombre del tipo de medio');
            $table->primary('tipmedid')->index('pk_tipmed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipomedio');
    }
};
