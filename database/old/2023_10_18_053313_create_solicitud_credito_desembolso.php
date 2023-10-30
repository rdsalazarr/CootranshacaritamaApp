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
        Schema::create('solicitudcreditodesembolso', function (Blueprint $table) {
            $table->increments('socrdeid')->unsigned()->comment('Identificador de la tabla solicitud de credito desembolso');
            $table->integer('solcreid')->unsigned()->comment('Identificador de la solicitud de crédito');
            $table->date('socrdefechadesembolso')->comment('Fecha de desembolso del crédito');           
            $table->string('socrdeanio', 4)->comment('Año en el cual se desembolsa el crédito');
            $table->string('socrdenumerodesembolso', 3)->comment('Número de desembolso asignado por cada año');
            $table->string('socrdevalordesembolsado', 10)->comment('Monto o valor desembolsado');
            $table->decimal('socrdetasa',6,2)->comment('Tasa de interés aplicado en el desembolso');
            $table->string('socrdenumerocuota', 3)->comment('Número de cuota aprobado en el desembolso');
            $table->timestamps();

            $table->foreign('solcreid')->references('solcreid')->on('solicitudcredito')->onUpdate('cascade')->index('fk_solcresocrde'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudcreditodesembolso');
    }
};
