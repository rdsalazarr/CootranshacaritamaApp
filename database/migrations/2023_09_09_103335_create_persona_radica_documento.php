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
        Schema::create('personaradicadocumento', function (Blueprint $table) {
            $table->increments('peradoid')->unsigned()->comment('Identificador de la tabla persona radica documento');
            $table->tinyInteger('tipideid')->unsigned()->comment('Identificador del tipo de identificación');
            $table->string('peradodocumento', 15)->comment('Número de documento de la persona');
            $table->string('peradoprimernombre', 40)->comment('Nombre de la persona que radica el documento');
            $table->string('peradosegundonombre', 40)->nullable()->comment('Nombre de la persona que radica el documento');
            $table->string('peradoprimerapellido', 40)->nullable()->comment('Nombre de la persona que radica el documento');
            $table->string('peradosegundoapellido', 40)->nullable()->comment('Nombre de la persona que radica el documento');
            $table->string('peradodireccion', 100)->comment('Dirección de la persona que radica el documento');
            $table->string('peradotelefono', 20)->nullable()->comment('Telefóno de la persona que radica el documento');
            $table->string('peradocorreo', 80)->nullable()->comment('correo de la persona que radica el documento');
            $table->string('peradocodigodocumental', 20)->nullable()->comment('Código documental proveniente de la emprea que emite el documento');
            $table->unique(['tipideid','peradodocumento'],'uk_personaradicadocumento');
            $table->foreign('tipideid')->references('tipideid')->on('tipoidentificacion')->onUpdate('cascade')->index('fk_tipideperado');
            $table->timestamps();
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personaradicadocumento');
    }
};
