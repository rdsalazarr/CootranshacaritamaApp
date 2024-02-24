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
            $table->tinyInteger('depaidorigen')->unsigned()->comment('Identificador del departamento de origen de la ruta');
            $table->smallInteger('muniidorigen')->unsigned()->comment('Identificador del municipio de origen de la ruta');
            $table->tinyInteger('depaiddestino')->unsigned()->comment('Identificador del departamento de destino de la ruta');
            $table->smallInteger('muniiddestino')->unsigned()->comment('Identificador del municipio de destino de la ruta');
            $table->decimal('rutavalorestampilla', 6, 0)->comment('Valor de la estampilla para la ruta');
            $table->boolean('rutatienenodos')->default(false)->comment('Determina si la ruta tiene nodos');
            $table->boolean('rutaactiva')->default(true)->comment('Determina si la ruta se encuentra activa');
            $table->timestamps();
            $table->unique(['depaidorigen','muniidorigen','depaiddestino','muniiddestino'],'uk_ruta');
            $table->foreign('depaidorigen')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_deparutaorigen');
            $table->foreign('muniidorigen')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munirutaorigen');
            $table->foreign('depaiddestino')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_deparutadestino');
            $table->foreign('muniiddestino')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munirutadestino');
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
