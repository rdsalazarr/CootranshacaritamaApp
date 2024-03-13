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
        Schema::create('fidelizacioncliente', function (Blueprint $table) {
            $table->tinyInteger('fidcliid')->unsigned()->comment('Identificador de la tabla compañia aseguradora');
            $table->decimal('fidclivalorfidelizacion', 6, 0)->comment('Valor de a fidelización para el cliente');
            $table->decimal('fidclivalorpunto', 6, 0)->comment('Valor del punto de la fidelización');
            $table->decimal('fidclipuntosminimoredimir', 6, 0)->comment('Puntos mínimo para redimir en la la fidelización');
            $table->primary('fidcliid')->index('pk_fidcli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fidelizacioncliente');
    }
};
