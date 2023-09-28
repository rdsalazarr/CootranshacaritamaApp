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
        Schema::create('archivohistoricodigitalizado', function (Blueprint $table) {
            $table->increments('arhidiid')->unsigned()->comment('Identificador de la tabla radicacion documento entrante dependencia');
            $table->bigInteger('archisid')->unsigned()->comment('Identificador del archivo histórico');
            $table->string('arhidinombrearchivooriginal', 200)->comment('Nombre con el cual se ha subido el archivo digitalizado');
            $table->string('arhidinombrearchivoeditado', 200)->comment('Nombre con el cual se ha subido el archivo digitalizado pero editado');
            $table->string('arhidirutaarchivo', 500)->comment('Ruta enfuscada del archivo digitalizado');
            $table->timestamps();
            $table->foreign('archisid')->references('archisid')->on('archivohistorico')->onUpdate('cascade')->index('fk_archisarhidi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivohistoricodigitalizado');
    }
};
