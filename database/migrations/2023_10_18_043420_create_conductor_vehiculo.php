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
        Schema::create('conductorvehiculo', function (Blueprint $table) {
            $table->bigIncrements('convehid')->unsigned()->comment('Identificador de la tabla conductor vehículo');
            $table->integer('condid')->unsigned()->comment('Identificador del conductor');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->timestamps();
            $table->foreign('condid')->references('condid')->on('conductor')->onUpdate('cascade')->index('fk_condconveh');
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehiconveh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conductorvehiculo');
    }
};
