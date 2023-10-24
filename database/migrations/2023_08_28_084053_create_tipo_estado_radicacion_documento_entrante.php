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
        Schema::create('tipoestadoraddocentrante', function (Blueprint $table) {
            $table->tinyInteger('tierdeid')->unsigned()->comment('Identificador de la tabla tipo estado documento entrante');
            $table->string('tierdenombre', 30)->comment('Nombre del tipo estado documento entrante');
            $table->primary('tierdeid')->index('pk_tierde');
        });
    }

    /*  Inicial
        Tramitado      
        Recibido       
        Respondido
        Anulado
    */

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestadoraddocentrante');
    }
};
