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
        Schema::create('tarifatiquete', function (Blueprint $table) {
            $table->increments('tartiqid')->unsigned()->comment('Identificador de la tabla tarifa tiquete');
            $table->integer('rutaid')->unsigned()->comment('Identificador de la ruta');
            $table->tinyInteger('tartiqdepaidorigen')->unsigned()->comment('Identificador del departamento de origen del tiquete');
            $table->smallInteger('tartiqmuniidorigen')->unsigned()->comment('Identificador del municipio de origen del tiquete');
            $table->tinyInteger('tartiqdepaiddestino')->unsigned()->comment('Identificador del departamento de destino del tiquete');
            $table->smallInteger('tartiqmuniiddestino')->unsigned()->comment('Identificador del municipio de destino del tiquete');
            $table->decimal('tartiqvalor', 10, 0)->comment('Valor del tiquete');
            $table->decimal('tartiqvalorseguro', 6, 0)->default(0)->comment('Valor del seguro para el tiquete');
            $table->decimal('tartiqvalorestampilla', 6, 0)->nullable()->comment('Valor de la estampilla para el tiquete');
            $table->decimal('tartiqfondoreposicion', 6, 2)->comment('Porcentaje para el fondo de reposición del tiquete');
            $table->timestamps();
            $table->foreign('rutaid')->references('rutaid')->on('ruta')->onUpdate('cascade')->index('fk_rutatartiq');
            $table->foreign('tartiqdepaidorigen')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depatartiqorigen');
            $table->foreign('tartiqmuniidorigen')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munitartiqorigen');
            $table->foreign('tartiqdepaiddestino')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depatartiqdestino');
            $table->foreign('tartiqmuniidestino')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munitartiqdestino');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifatiquete');
    }
};
