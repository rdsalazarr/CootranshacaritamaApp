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
        Schema::create('coddocumprocesoanexo', function (Blueprint $table) {
            $table->bigIncrements('codopxid')->unsigned()->comment('Identificador de la tabla codigo documental proceso anexo');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');    
            $table->string('codopxnombreanexo', 200)->comment('Nombre con el cual se ha subido el documento');
            $table->string('codopxrutaanexo', 500)->comment('Ruta enfuscada del anexo para el documento');
            $table->timestamps();            
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopx'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesoanexo');
    }
};
