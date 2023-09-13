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
        Schema::create('tipoestadodocumento', function (Blueprint $table) {
            $table->tinyInteger('tiesdoid')->unsigned()->comment('Identificador de la tabla tipo estado documento');
            $table->string('tiesdonombre', 50)->comment('Nombre del tipo estado documento');;
            $table->primary('tiesdoid')->index('pk_tiesdo');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoestadodocumento');
    }
};
