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
        Schema::create('conductorcambioestado', function (Blueprint $table) {
            $table->increments('cocaesid')->unsigned()->comment('Identificador de la tabla conductor cambio estado');
            $table->integer('condid')->unsigned()->comment('Identificador del conductor');
            $table->string('tiescoid', 2)->comment('Identificador del tipo de estado conductor');
            $table->smallInteger('cocaesusuaid')->unsigned()->comment('Identificador del usuario que crea el estado del conductor');
            $table->dateTime('cocaesfechahora')->comment('Fecha y hora en la cual se crea el cambio estado del conductor');
            $table->string('cocaesobservacion', 500)->nullable()->comment('Observación del cambio estado del conductor');
            $table->timestamps();
            $table->foreign('condid')->references('condid')->on('conductor')->onUpdate('cascade')->index('fk_condcocaes'); 
            $table->foreign('tiescoid')->references('tiescoid')->on('tipoestadoconductor')->onUpdate('cascade')->index('fk_tiesascocaes'); 
            $table->foreign('cocaesusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacocaes'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductorcambioestado');
    }
};
