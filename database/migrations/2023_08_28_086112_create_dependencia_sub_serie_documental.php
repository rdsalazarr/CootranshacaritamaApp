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
            $table->increments('desusdid')->index('pk_desuse')->comment('Identificador de la tabla dependencia sub serie documental');
            $table->mediumInteger('desusesubserid')->unsigned()->comment('Identificador de la tabla sub serie');
            $table->smallInteger('desusedepeid')->unsigned()->comment('Identificador de la tabla dependencia');
           
            $table->foreign('desusesubserid')->references('subserid')->on('subserie')->onUpdate('cascade')->index('fk_desusesubser'); 
            $table->foreign('desusedepeid')->references('depeid')->on('dependencia')->onUpdate('cascade')->index('fk_desusedepe'); 
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
