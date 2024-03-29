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
        Schema::create('colocacion', function (Blueprint $table) {
            $table->increments('coloid')->unsigned()->comment('Identificador de la tabla solicitud de credito desembolso');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea la colocación');
            $table->integer('solcreid')->unsigned()->comment('Identificador de la solicitud de crédito');
            $table->string('tiesclid', 2)->comment('Identificador del tipo estado colocación');
            $table->dateTime('colofechahoradesembolso')->comment('Fecha y hora actual en el que se registra el desembolso de la colocacion');
            $table->date('colofechacolocacion')->comment('Fecha de desembolso del la colocacion');
            $table->year('coloanio', 4)->comment('Año en el cual se desembolsa del la colocacion');
            $table->string('colonumerodesembolso', 4)->comment('Número de desembolso asignado por cada año');
            $table->decimal('colovalordesembolsado', 12, 0)->comment('Monto o valor desembolsado');
            $table->decimal('colotasa',6,2)->comment('Tasa de interés aplicado en el desembolso');
            $table->decimal('colonumerocuota', 5, 0)->comment('Número de cuota aprobado en el desembolso');
            $table->boolean('colocontabilizada')->default(false)->comment('Determina si la colocación ha sido contabilizada'); 
            $table->timestamps();
            $table->unique(['coloanio','colonumerodesembolso'],'uk_colocacion');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacolo');
            $table->foreign('solcreid')->references('solcreid')->on('solicitudcredito')->onUpdate('cascade')->index('fk_solcrecolo');
            $table->foreign('tiesclid')->references('tiesclid')->on('tipoestadocolocacion')->onUpdate('cascade')->index('fk_tiesclcolo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colocacion');
    }
};
