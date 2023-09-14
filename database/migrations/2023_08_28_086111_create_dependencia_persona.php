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
        Schema::create('dependenciapersona', function (Blueprint $table) {
            $table->increments('depperid')->unsigned()->comment('Identificador de la tabla dependencia persona');  
            $table->smallInteger('depperdepeid')->unsigned()->comment('Identificador de la dependencia');
            $table->integer('depperpersid')->unsigned()->comment('Identificador del persona asignado a la dependencia');  
            
            $table->unique(['depperdepeid','depperpersid'],'uk_dependenciapersona');            
            $table->foreign('depperdepeid')->references('depeid')->on('dependencia')->onUpdate('cascade')->index('fk_depedepper'); 
            $table->foreign('depperpersid')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_persdepper'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependenciapersona');
    }
};
