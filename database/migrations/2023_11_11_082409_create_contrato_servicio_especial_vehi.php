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
        Schema::create('contratoservicioespecialvehi', function (Blueprint $table) {
            $table->bigIncrements('coseevid')->unsigned()->comment('Identificador de la tabla contrato servicio especial vehículo');
            $table->integer('coseesid')->unsigned()->comment('Identificador del contrato servicio especial');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->year('coseevextractoanio', 4)->comment('Año en el cual se realiza el extracto contrato de servicio especial para el vehículo');
            $table->string('coseevextractoconsecutivo', 4)->comment('Consecutivo en el cual se realiza el extracto contrato de servicio especial para el vehículo');
            $table->timestamps();
            $table->foreign('coseesid')->references('coseesid')->on('contratoservicioespecial')->onUpdate('cascade')->index('fk_coseescoseev');
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehicoseev');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratoservicioespecialvehi');
    }
};