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
        Schema::create('coddocumprocesofirma', function (Blueprint $table) {
            $table->bigIncrements('codopfid')->unsigned()->comment('Identificador de la tabla codigo documental proceso firma');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->integer('persid')->unsigned()->comment('Identificador de la tabla persona');
            $table->smallInteger('carlabid')->nullable()->unsigned()->comment('Identificador de la tabla cargo laboral');
            $table->boolean('codopffirmado')->default(false)->comment('Determina si el documento esta firmado');
            $table->boolean('codopfesinvitado')->default(false)->comment('Determina si el que firma es invitado en el acta');
            $table->timestamps();
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopf'); 
            $table->foreign('persid')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_perscodopf');
            $table->foreign('carlabid')->references('carlabid')->on('cargolaboral')->onUpdate('cascade')->index('fk_carlabcodopf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesofirma');
    }
};
