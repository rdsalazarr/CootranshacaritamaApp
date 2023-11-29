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
        Schema::create('colocacionliquidacion', function (Blueprint $table) {
            $table->bigIncrements('colliqid')->unsigned()->comment('Identificador de la tabla colocación liquidación');
            $table->integer('coloid')->unsigned()->comment('Identificador de la solicitud de crédito');
            $table->string('colliqnumerocuota', 3)->comment('Número de cuota de la colocación');
            $table->string('colliqvalorcuota', 10)->comment('Monto o valor de la cuota de la colocación');
            $table->date('colliqfechavencimiento')->comment('Fecha de vencimiento de la cuota de la colocación');
            $table->date('colliqfechapago')->nullable()->comment('Fecha de pago de la cuota de la colocación');
            $table->string('colliqnumerocomprobante', 10)->nullable()->comment('Número de comprobante de pago de la cuota de la colocación');
            $table->decimal('colliqvalorpagado', 12, 2)->nullable()->comment('Valor pagado en la cuota de la colocación');
            $table->decimal('colliqsaldocapital', 10, 2)->nullable()->comment('Saldo a capital de la colocación');
            $table->decimal('colliqvalorcapitalpagado', 10, 2)->nullable()->comment('Valor capital pagado la colocación');
            $table->decimal('colliqvalorinterespagado', 10, 2)->nullable()->comment('Valor interés pagado la colocación');
            $table->decimal('colliqvalorinteresmora', 10, 2)->nullable()->comment('Valor interés de mora pagado la colocación');
            $table->timestamps();
            $table->foreign('coloid')->references('coloid')->on('colocacion')->onUpdate('cascade')->index('fk_colocolliq');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colocacionliquidacion');
    }
};
