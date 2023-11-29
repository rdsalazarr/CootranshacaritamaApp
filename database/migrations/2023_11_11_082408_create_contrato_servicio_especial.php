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
        Schema::create('contratoservicioespecial', function (Blueprint $table) {
            $table->increments('coseesid')->unsigned()->comment('Identificador de la tabla contrato servicio especial');
            $table->integer('pecoseid')->unsigned()->comment('Identificador de la persona que contrata el servicio especial');
            $table->integer('persidgerente')->unsigned()->comment('Identificador de la persona');
            $table->string('ticoseid', 2)->comment('Identificador del tipo contrato servicio especial');
            $table->string('ticossid', 2)->comment('Identificador del tipo contrato servicio especial');
            $table->dateTime('coseesfechahora')->comment('Fecha y hora actual en el que se registra el contrato de servicio especial');
            $table->year('coseesanio', 4)->comment('Año en el cual se realiza el contrato de servicio especial');
            $table->string('coseesconsecutivo', 4)->comment('Consecutivo del contrato de servicio especial dado por cada año');
            $table->date('coseesfechaincial')->comment('Fecha de inicio del contrato de servicio especial');
            $table->date('coseesfechafinal')->comment('Fecha final del contrato de servicio especial');
            $table->string('coseesvalorcontrato', 20)->comment('Valor del contrato de servicio especial');
            $table->string('coseesorigen', 100)->comment('Origen del contrato de servicio especial');
            $table->string('coseesdestino', 100)->comment('Destino del contrato de servicio especial');
            $table->string('coseesdescripcionrecorrido', 1000)->comment('Descripción del recorrido para el contrato de servicio especial');
            $table->string('coseesnombreuniontemporal', 100)->nullable()->comment('Nombre de la unión temporal para el contrato de servicio especial');
            $table->string('coseesobservacion', 1000)->nullable()->comment('Observación del contrato de servicio especial');
            $table->timestamps();
            $table->unique(['coseesanio','coseesconsecutivo'],'uk_contratoservicioespecial');
            $table->foreign('pecoseid')->references('pecoseid')->on('personacontratoservicioesp')->onUpdate('cascade')->index('fk_pecosecosees');
            $table->foreign('persidgerente')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_perscosees');
            $table->foreign('ticoseid')->references('ticoseid')->on('tipocontratoservicioespecial')->onUpdate('cascade')->index('fk_ticosecosees');
            $table->foreign('ticossid')->references('ticossid')->on('tipoconvenioservicioespecial')->onUpdate('cascade')->index('fk_ticosscosees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratoservicioespecial');
    }
};