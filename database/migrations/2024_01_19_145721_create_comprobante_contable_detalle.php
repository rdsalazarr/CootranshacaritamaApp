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
        Schema::create('comprobantecontabledetalle', function (Blueprint $table) {
            $table->bigIncrements('cocodeid')->unsigned()->comment('Identificador de la tabla movimiento caja detallado');
            $table->bigInteger('comconid')->unsigned()->comment('Identificador del comprobante contable');
            $table->integer('cueconid')->unsigned()->comment('Identificador de la cuenta contable');
            $table->dateTime('cocodefechahora')->comment('Fecha y hora en la cual se realiza el registro');
            $table->decimal('cocodemonto', 12, 2)->nullable()->comment('Monto del movimiento de caja detallado');
            $table->boolean('cocodecontabilizado')->default(false)->comment('Determina si el movimiento fue contabilizado');
            $table->timestamps();
            $table->foreign('comconid')->references('comconid')->on('comprobantecontable')->onUpdate('cascade')->index('fk_comconcocode');
            $table->foreign('cueconid')->references('cueconid')->on('cuentacontable')->onUpdate('cascade')->index('fk_cueconcocode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobantecontabledetalle');
    }
};
