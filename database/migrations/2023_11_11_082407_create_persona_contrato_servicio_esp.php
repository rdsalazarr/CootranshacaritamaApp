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
        Schema::create('personacontratoservicioesp', function (Blueprint $table) {
            $table->increments('pecoseid')->unsigned()->comment('Identificador de la tabla persona contrato servicio especial');
            $table->tinyInteger('tipideid')->unsigned()->comment('Identificador del tipo de identificación');
            $table->string('pecosedocumento', 15)->comment('Número de documento de la persona que contrata el servicio especial');
            $table->string('pecoseprimernombre', 140)->comment('Primer nombre de la persona que contrata el servicio especial');
            $table->string('pecosesegundonombre', 40)->nullable()->comment('Segundo nombre de la persona que contrata el servicio especial');
            $table->string('pecoseprimerapellido', 40)->nullable()->comment('Primer apellido de la persona que contrata el servicio especial');
            $table->string('pecosesegundoapellido', 40)->nullable()->comment('Segundo apellido de la persona que contrata el servicio especial');
            $table->string('pecosedireccion',100)->comment('Determina el genero de la persona que contrata el servicio especial');
            $table->string('pecosecorreoelectronico', 80)->nullable()->comment('Correo electrónico de la persona que contrata el servicio especial');
            $table->string('pecosenumerocelular', 20)->nullable()->comment('Número de teléfono fijo de la persona que contrata el servicio especial');
            $table->unique(['tipideid','pecosedocumento'],'uk_personacontratoservicioesp');
            $table->timestamps();
            $table->foreign('tipideid')->references('tipideid')->on('tipoidentificacion')->onUpdate('cascade')->index('fk_tipidepecose');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personacontratoservicioesp');
    }
};