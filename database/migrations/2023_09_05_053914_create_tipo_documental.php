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
        Schema::create('tipodocumental', function (Blueprint $table) {
            $table->tinyInteger('tipdocid')->unsigned()->comment('Identificador de la tabla tipo documental');
            $table->string('tipdoccodigo', 2)->unique('uk_tipodocumental')->comment('Código del tipo documental');
            $table->string('tipdocnombre', 50)->comment('Nombre del tipo documental');
            $table->boolean('tipdocactivo')->default(true)->comment('Determina si el tipo de documento se encuentra activo'); 
            $table->primary('tipdocid')->index('pk_tipdoc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipodocumental');
    }
};
