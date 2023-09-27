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
        Schema::create('radicaciondocentanexo', function (Blueprint $table) {
            $table->increments('radoeaid')->unsigned()->comment('Identificador de la tabla radicacion documento entrante dependencia');
            $table->integer('radoenid')->unsigned()->comment('Identificador del radicado del documento entrante');
            $table->string('radoeanombreanexooriginal', 200)->comment('Nombre con el cual se ha subido el documento');
            $table->string('radoeanombreanexoeditado', 200)->comment('Nombre con el cual se ha subido el documento pero editado');
            $table->string('radoearutaanexo', 500)->comment('Ruta enfuscada del anexo del radicado');
            $table->boolean('radoearequiereradicado')->default(false)->comment('Determina si el adjunto requiere radicado');
            $table->timestamps();
            $table->foreign('radoenid')->references('radoenid')->on('radicaciondocumentoentrante')->onUpdate('cascade')->index('fk_radoenradoea');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radicaciondocentanexo');
    }
};
