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
        Schema::create('dependenciasubseriedocumental', function (Blueprint $table) {
            $table->increments('desusdid')->unsigned()->comment('Identificador de la tabla dependencia sub serie documental');
            $table->mediumInteger('desusdsusedoid')->unsigned()->comment('Identificador de la tabla sub serie');
            $table->smallInteger('desusddepeid')->unsigned()->comment('Identificador de la tabla dependencia');
 
            $table->foreign('desusdsusedoid')->references('susedoid')->on('subseriedocumental')->onUpdate('cascade')->index('fk_susedodesusd'); 
            $table->foreign('desusddepeid')->references('depeid')->on('dependencia')->onUpdate('cascade')->index('fk_depedesusd'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependenciasubseriedocumental');
    }
};
