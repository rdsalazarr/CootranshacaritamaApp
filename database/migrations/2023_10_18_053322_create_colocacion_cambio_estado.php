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
        Schema::create('colocacioncambioestado', function (Blueprint $table) {
            $table->bigIncrements('cocaesid')->unsigned()->comment('Identificador de la tabla colocación cambio estado');
            $table->integer('coloid')->unsigned()->comment('Identificador de la colocación');
            $table->string('tiesclid', 2)->comment('Identificador del tipo de estado colocación');
            $table->smallInteger('cocaesusuaid')->unsigned()->comment('Identificador del usuario que crea el estado de la colocación');
            $table->dateTime('cocaesfechahora')->comment('Fecha y hora en la cual se crea el cambio estado de la colocación');
            $table->string('cocaesobservacion', 500)->nullable()->comment('Observación del cambio estado de la colocación');
            $table->timestamps();
            $table->foreign('coloid')->references('coloid')->on('colocacion')->onUpdate('cascade')->index('fk_colococaes');
            $table->foreign('tiesclid')->references('tiesclid')->on('tipoestadocolocacion')->onUpdate('cascade')->index('fk_tiesclcocaes');
            $table->foreign('cocaesusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacocaes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colocacioncambioestado');
    }
};
