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
        Schema::create('personaservicio', function (Blueprint $table) {
            $table->increments('perserid')->unsigned()->comment('Identificador de la tabla persona servicio');
            $table->tinyInteger('tipideid')->unsigned()->comment('Identificador del tipo de identificación');
            $table->string('perserdocumento', 15)->comment('Número de documento de la persona que utiliza un servicio de la cooperativa (pasaje o encomienda)');
            $table->string('perserprimernombre', 140)->comment('Primer nombre de la persona que un servicio de la cooperativa (pasaje o encomienda)');
            $table->string('persersegundonombre', 40)->nullable()->comment('Segundo nombre de la persona que un servicio de la cooperativa (pasaje o encomienda)');
            $table->string('perserprimerapellido', 40)->nullable()->comment('Primer apellido de la persona que un servicio de la cooperativa (pasaje o encomienda)');
            $table->string('persersegundoapellido', 40)->nullable()->comment('Segundo apellido de la persona que un servicio de la cooperativa (pasaje o encomienda)');
            $table->string('perserdireccion',100)->comment('Dirección de la persona que un servicio de la cooperativa (pasaje o encomienda)');
            $table->string('persercorreoelectronico', 80)->nullable()->comment('Correo electrónico de la persona que un servicio de la cooperativa (pasaje o encomienda)');
            $table->string('persernumerocelular', 20)->nullable()->comment('Número de teléfono fijo de la persona que un servicio de la cooperativa (pasaje o encomienda)');
            $table->unique(['tipideid','perserdocumento'],'uk_personaservicio');
            $table->timestamps();
            $table->foreign('tipideid')->references('tipideid')->on('tipoidentificacion')->onUpdate('cascade')->index('fk_tipideperser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personaservicio');
    }
};
