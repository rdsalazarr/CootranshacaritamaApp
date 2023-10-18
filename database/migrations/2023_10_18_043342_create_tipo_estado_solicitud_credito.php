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
        Schema::create('tipoestadosolicitudcredito', function (Blueprint $table) {
            $table->string('tiesscid', 2)->comment('Identificador del tipo de estado de la solicitud de crédito');
            $table->string('tiesscnombre', 30)->comment('Nombre del tipo de estado de la solicitud de crédito');
            $table->primary('tiesscid')->index('pk_tiessc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestadosolicitudcredito');
    }
};
