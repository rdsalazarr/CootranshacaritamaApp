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
        Schema::create('archivohistorico', function (Blueprint $table) {
            $table->bigIncrements('archisid')->unsigned()->comment('Identificador de la tabla archivo histórico');
            $table->tinyInteger('tipdocid')->unsigned()->comment('Identificador de la tipo documento');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el registro del documento');
            $table->smallInteger('tiesarid')->unsigned()->comment('Identificador del tipo estante archivador');
            $table->tinyInteger('ticaubid')->unsigned()->comment('Identificador del tipo de caja ubicacion');
            $table->tinyInteger('ticrubid')->unsigned()->comment('Identificador del tipo de carpeta ubicacion');
            $table->dateTime('archisfechahora')->comment('Fecha y hora actual en el que se crea el registro del documento');
            $table->date('archisfechadocumento')->nullable()->comment('Fecha que contiene el documento');
            $table->string('archisnumerofolio', 2)->comment('Número de folio que posee el documento del archivo histórico'); 
            $table->string('archisasuntodocumento', 200)->comment('Asunto que posee el documento del archivo histórico');
            $table->string('archistomodocumento', 2)->nullable()->comment('Tomo que posee el documento del archivo histórico');
            $table->string('archiscodigodocumental', 20)->nullable()->comment('Código que posee el documento del archivo histórico');
            $table->string('archisentidadremitente', 200)->nullable()->comment('Entidad remitente que posee el documento del archivo histórico');
            $table->string('archisentidadproductora', 200)->nullable()->comment('Entidad productora que posee el documento del archivo histórico');
            $table->string('archisresumendocumento', 500)->nullable()->comment('REsumen que posee el documento del archivo histórico');
            $table->string('archisobservacion', 500)->nullable()->comment('Observación general del registro del archivo histórico');
            $table->timestamps();
            $table->foreign('tipdocid')->references('tipdocid')->on('tipodocumental')->onUpdate('cascade')->index('fk_tipdocarchis');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuaarchis');
            $table->foreign('tiesarid')->references('tiesarid')->on('tipoestantearchivador')->onUpdate('cascade')->index('fk_tiesararchis'); 
            $table->foreign('ticaubid')->references('ticaubid')->on('tipocajaubicacion')->onUpdate('cascade')->index('fk_ticauarchis'); 
            $table->foreign('ticrubid')->references('ticrubid')->on('tipocarpetaubicacion')->onUpdate('cascade')->index('fk_ticrubarchis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archivohistorico');
    }
};
