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
        Schema::create('ruta', function (Blueprint $table) {
            $table->increments('rutaid')->unsigned()->comment('Identificador de la tabla ruta');
            $table->tinyInteger('rutadepaidorigen')->unsigned()->comment('Identificador del departamento de origen de la ruta');
            $table->smallInteger('rutamuniidorigen')->unsigned()->comment('Identificador del municipio de origen de la ruta');
            $table->tinyInteger('rutadepaiddestino')->unsigned()->comment('Identificador del departamento de destino de la ruta');
            $table->smallInteger('rutamuniiddestino')->unsigned()->comment('Identificador del municipio de destino de la ruta');
            $table->boolean('rutatienenodos')->default(false)->comment('Determina si la ruta tiene nodos');
            $table->boolean('rutaactiva')->default(true)->comment('Determina si la ruta se encuentra activa');
            $table->timestamps();
            $table->unique(['rutadepaidorigen','rutamuniidorigen','rutadepaiddestino','rutamuniiddestino'],'uk_ruta');
            $table->foreign('rutadepaidorigen')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_deparutaorigen');
            $table->foreign('rutamuniidorigen')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munirutaorigen');
            $table->foreign('rutadepaiddestino')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_deparutadestino');
            $table->foreign('rutamuniiddestino')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munirutadestino');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta');
    }
};
