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
        Schema::create('vehiculoresponpagoparcial', function (Blueprint $table) {
            $table->bigIncrements('vereppid')->unsigned()->comment('Identificador de la tabla vehículo responsabilidad pago parcial');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia a la que recibe el pago');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que recibe el pago');  
            $table->decimal('vereppvalorpagado', 8, 0)->comment('Valor parcial pagado referente a la responsabilidad mensual');
            $table->datetime('vereppfechapagado')->comment('Fecha en la cual se realiza el pago parcial de la responsabilidad');
            $table->boolean('vereppprocesado')->default(false)->comment('Determina si el pago parcial esta procesado');
            $table->timestamps();
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehiverepp');
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agenverepp');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaverepp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculoresponpagoparcial');
    }
};
