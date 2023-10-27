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
        Schema::create('tipoestadovehiculo', function (Blueprint $table) {        
            $table->string('tiesveid', 2)->comment('Identificador de la tabla tipo estado vehículo');
            $table->string('tiesvenombre', 50)->comment('Nombre del tipo estado vehículo');;
            $table->primary('tiesveid')->index('pk_tiesve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestadovehiculo');
    }
};
