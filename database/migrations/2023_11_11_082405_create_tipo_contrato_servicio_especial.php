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
        Schema::create('tipocontratoservicioespecial', function (Blueprint $table) {
            $table->string('ticoseid', 2)->comment('Identificador de la tabla tipo contrato servicio especial');
            $table->string('ticosenombre', 30)->comment('Nombre del tipo contrato servicio especial');
            $table->primary('ticoseid')->index('pk_ticose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipocontratoservicioespecial');
    }
};