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
        Schema::create('entidadfinanciera', function (Blueprint $table) {
            $table->increments('entfinid')->unsigned()->comment('Identificador de la tabla entidad finaciera');
            $table->string('entfinnombre', 100)->comment('Nombre de la entidad finaciera');
            $table->string('entfinnumerocuenta', 20)->nullable()->comment('Número de cuenta de la entidad finaciera');
            $table->boolean('entfinactiva')->default(true)->comment('Determina si la entidad finaciera se encuentra activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidadfinanciera');
    }
};
