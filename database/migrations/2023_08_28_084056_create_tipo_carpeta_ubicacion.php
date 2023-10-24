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
        Schema::create('tipocarpetaubicacion', function (Blueprint $table) {
            $table->tinyInteger('ticrubid')->unsigned()->comment('Identificador de la tabla tipo carpeta ubicación');
            $table->string('ticrubnombre', 30)->comment('Nombre del tipo de carpeta ubicación');
            $table->primary('ticrubid')->index('pk_ticrub');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipocarpetaubicacion');
    }
};
