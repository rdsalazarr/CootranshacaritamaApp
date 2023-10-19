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
            $table->timestamps();
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
