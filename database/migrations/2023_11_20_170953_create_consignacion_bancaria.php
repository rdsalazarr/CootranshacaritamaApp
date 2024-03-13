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
        Schema::create('consignacionbancaria', function (Blueprint $table) {
            $table->bigIncrements('conbanid')->unsigned()->comment('Identificador de la tabla consignacion bancaria');
            $table->integer('entfinid')->unsigned()->comment('Identificador de la entidad finaciera');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->smallInteger('agenid')->unsigned()->comment('Identificador de la agencia');
            $table->dateTime('conbanfechahora')->comment('Fecha y hora en la cual se registra la consignacion bancaria');
            $table->decimal('conbanmonto', 10, 2)->comment('Cantidad de dinero consignada');
            $table->string('conbandescripcion', 200)->nullable()->comment('Descripción de la consignación realizada');
            $table->timestamps();
            $table->foreign('entfinid')->references('entfinid')->on('entidadfinanciera')->onUpdate('cascade')->index('fk_entfinconban');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaconban');
            $table->foreign('agenid')->references('agenid')->on('agencia')->onUpdate('cascade')->index('fk_agenconban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consignacionbancaria');
    }
};
