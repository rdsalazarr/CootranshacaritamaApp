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
        Schema::create('subseriedocumental', function (Blueprint $table) {
            $table->mediumIncrements('susedoid')->unsigned()->comment('Identificador de la tabla sub serie documental');
            $table->smallInteger('serdocid')->unsigned()->comment('Identificador de la tabla serie documental');
            $table->tinyInteger('tipdocid')->unsigned()->comment('Identificador de la tabla tipo documento');
            $table->string('susedocodigo', 2)->comment('Código de la sub serie documental');
            $table->string('susedonombre', 80)->comment('Nombre de la sub serie documental');
            $table->boolean('susedopermiteeliminar')->default(true)->comment('Determina si la sub serie documental se puede eliminar');
            $table->boolean('susedoactiva')->default(false)->comment('Determina si la sub serie documental esta activa');
            $table->timestamps();
            $table->unique(['serdocid','susedocodigo'],'uk_serdocsusedo');
            $table->foreign('serdocid')->references('serdocid')->on('seriedocumental')->onUpdate('cascade')->index('fk_serdocsusedo');
            $table->foreign('tipdocid')->references('tipdocid')->on('tipodocumental')->onUpdate('cascade')->index('fk_tipdocsusedo'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subseriedocumental');
    }
};
