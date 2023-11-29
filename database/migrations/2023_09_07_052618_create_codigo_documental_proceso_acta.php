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
        Schema::create('coddocumprocesoacta', function (Blueprint $table) {
            $table->bigIncrements('codopaid')->unsigned()->comment('Identificador de la tabla codigo documental proceso acta');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->tinyInteger('tipactid')->unsigned()->comment('Identificador del tipo de acta');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el documento');
            $table->string('codopaconsecutivo', 4)->comment('Consecutivo del acta');
            $table->string('codopasigla', 3)->comment('Sigla de la dependencia productora del acta');
            $table->year('codopaanio', 4)->comment('Año en el cual se crea el acta');
            $table->string('codopahorainicio', 6)->comment('Hora de inicio del acta');
            $table->string('codopahorafinal', 6)->comment('Hora de final del acta');
            $table->string('codopalugar', 200)->comment('Lugar donde se realiza el acta');
            $table->string('codopaquorum', 200)->nullable()->comment('Quorum reglamentario para el acta');
            $table->string('codopaordendeldia', 4000)->comment('Orden del dñia del acta');
            $table->string('codopainvitado', 4000)->nullable()->comment('Personas invitados para el acta');
            $table->string('codopaausente', 4000)->nullable()->comment('Persona usente en la generación para el acta');
            $table->boolean('codopaconvocatoria')->default(false)->comment('Determina si el acta tiene conovocatoria');
            $table->string('codopaconvocatorialugar', 100)->nullable()->comment('Lugar conovocatoria para el acta');
            $table->date('codopaconvocatoriafecha')->nullable()->comment('Fecha para la conovocatoria del acta');
            $table->string('codopaconvocatoriahora', 6)->nullable()->comment('Hora de la conovocatoria del acta');
            $table->timestamps();
            $table->unique(['codopaconsecutivo','codopasigla','codopaanio'],'uk_coddocumprocesoacta');
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopa');
            $table->foreign('tipactid')->references('tipactid')->on('tipoacta')->onUpdate('cascade')->index('fk_tipactcodopa');
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodopa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coddocumprocesoacta');
    }
};
