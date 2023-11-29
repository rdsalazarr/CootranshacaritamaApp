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
        Schema::create('solicitudcredito', function (Blueprint $table) {
            $table->increments('solcreid')->unsigned()->comment('Identificador de la tabla solicitud crédito');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea la solicitud de crédito');
            $table->integer('lincreid')->unsigned()->comment('Identificador de la línea de crédito');
            $table->integer('asocid')->unsigned()->comment('Identificador del asociado');
            $table->integer('vehiid')->unsigned()->comment('Identificador del vehículo');
            $table->string('tiesscid', 2)->comment('Identificador del tipo de estado de la solicitud de crédito');
            $table->date('solcrefechasolicitud')->comment('Fecha de registro de la solicitud de crédito');
            $table->string('solcredescripcion', 1000)->comment('Descripción de la solicitud de crédito');
            $table->decimal('solcrevalorsolicitado', 12, 0)->comment('Monto o valor de la solicitud de crédito');
            $table->decimal('solcretasa',6, 2)->comment('Tasa de interés para solicitud de crédito');
            $table->decimal('solcrenumerocuota', 5, 0)->comment('Número de cuota de la solicitud de crédito');
            $table->string('solcreobservacion', 1000)->nullable()->comment('Observación general de la  solicitud de crédito');
            $table->timestamps();
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuasolcre'); 
            $table->foreign('lincreid')->references('lincreid')->on('lineacredito')->onUpdate('cascade')->index('fk_lincresolcre');
            $table->foreign('asocid')->references('asocid')->on('asociado')->onUpdate('cascade')->index('fk_asocsolcre');
            $table->foreign('vehiid')->references('vehiid')->on('vehiculo')->onUpdate('cascade')->index('fk_vehisolcre');
            $table->foreign('tiesscid')->references('tiesscid')->on('tipoestadosolicitudcredito')->onUpdate('cascade')->index('fk_tiesscsolcre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudcredito');
    }
};
