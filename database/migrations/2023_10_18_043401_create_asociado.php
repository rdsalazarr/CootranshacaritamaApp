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
        Schema::create('asociado', function (Blueprint $table) {
            $table->increments('asocid')->unsigned()->comment('Identificador de la tabla asociado');
            $table->integer('persid')->unsigned()->comment('Identificador de la persona');
            $table->string('tiesasid', 2)->comment('Identificador del tipo de estado del asociado');
            $table->date('asocfechaingreso')->comment('Fecha de ingreso del asocado a la cooperativa');
            $table->date('asocfecharetiro')->nullable()->comment('Fecha de retiro del asocado a la cooperativa');
            $table->string('asocrutacertificado', 500)->nullable()->comment('Ruta enfuscada del certifcado del asociado');
            $table->timestamps();
            $table->foreign('persid')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_persasoc');
            $table->foreign('tiesasid')->references('tiesasid')->on('tipoestadoasociado')->onUpdate('cascade')->index('fk_tiesasasoc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asociado');
    }
};
