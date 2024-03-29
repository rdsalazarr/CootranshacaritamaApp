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
        Schema::create('rutanodo', function (Blueprint $table) {
            $table->bigIncrements('rutnodid')->unsigned()->comment('Identificador de la tabla ruta nodo');
            $table->integer('rutaid')->unsigned()->comment('Identificador de la ruta');
            $table->tinyInteger('rutnoddepaid')->unsigned()->comment('Identificador del departamento del nodo de la ruta');
            $table->smallInteger('rutnodmuniid')->unsigned()->comment('Identificador del municipio del nodo de la ruta');
            $table->timestamps();
            $table->foreign('rutaid')->references('rutaid')->on('ruta')->onUpdate('cascade')->index('fk_rutarutnod');
            $table->foreign('rutnoddepaid')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_deparutnod');
            $table->foreign('rutnodmuniid')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munirutnod');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutanodo');
    }
};
