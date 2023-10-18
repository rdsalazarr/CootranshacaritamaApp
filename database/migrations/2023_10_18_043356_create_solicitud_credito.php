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
            $table->bigIncrements('solcreid')->unsigned()->comment('Identificador de la tabla solicitud crédito');
            $table->integer('lincreid')->unsigned()->comment('Identificador de la línea de crédito');
            $table->integer('asocid')->unsigned()->comment('Identificador del asociado');
            $table->string('tiesscid', 2)->unsigned()->comment('Identificador del tipo de estado de la solicitud de crédito');

            $table->timestamps();
            $table->foreign('lincreid')->references('lincreid')->on('lineacredito')->onUpdate('cascade')->index('fk_lincresolcre');
            $table->foreign('asocid')->references('asocid')->on('asociado')->onUpdate('cascade')->index('fk_asocsolcre');
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
