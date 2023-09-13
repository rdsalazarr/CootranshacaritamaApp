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
        Schema::create('tipodestino', function (Blueprint $table) {
            $table->tinyInteger('tipdetid')->unsigned()->comment('Identificador del tipo de destino');
            $table->string('tipdetnombre', 30)->comment('Nombre del tipo de destino');
            $table->primary('tipdetid')->index('pk_tipdes');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipodestino');
    }
};
