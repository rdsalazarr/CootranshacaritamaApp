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
        Schema::create('ingresosistema', function (Blueprint $table) {   
            $table->bigIncrements('ingsisid')->unsigned()->comment('Identificador de la tabla ingreso sistema');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->string('ingsisipacceso', 20)->comment('Ip de la cual accede el usuario al sistema');
            $table->dateTime('ingsisfechahoraingreso')->comment('Fecha y hora de ingreso al sistema'); 
            $table->dateTime('ingsisfechahorasalida')->nullable()->comment('Fecha y hora de ingreso al sistema');
            $table->timestamps();
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaingsis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingresosistema');
    }
};
