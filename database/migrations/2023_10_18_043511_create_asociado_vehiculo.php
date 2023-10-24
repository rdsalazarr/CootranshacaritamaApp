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
        Schema::create('asociadovehiculo', function (Blueprint $table) {
            $table->bigIncrements('asovehid')->unsigned()->comment('Identificador de la tabla asociado vehículo');
            $table->integer('asocid')->unsigned()->comment('Identificador del asociado');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->timestamps();
            $table->foreign('asocid')->references('asocid')->on('asociado')->onUpdate('cascade')->index('fk_asocasoveh');
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehiasoveh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asociadovehiculo');
    }
};
