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
        Schema::create('tipoconvenioservicioespecial', function (Blueprint $table) {
            $table->string('ticossid', 2)->comment('Identificador de la tabla tipo contrato servicio especial');
            $table->string('ticossnombre', 30)->comment('Nombre del tipo contrato servicio especial');
            $table->primary('ticossid')->index('pk_ticoss');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoconvenioservicioespecial');
    }
};