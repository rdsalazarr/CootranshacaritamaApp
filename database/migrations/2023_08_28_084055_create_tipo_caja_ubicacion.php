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
        Schema::create('tipocajaubicacion', function (Blueprint $table) {
            $table->tinyInteger('ticaubid')->unsigned()->comment('Identificador del tipo de caja ubicación');
            $table->string('ticaubnombre', 30)->comment('Nombre del tipo de caja ubicación');
            $table->primary('ticaubid')->index('pk_ticaub');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipocajaubicacion');
    }
};
