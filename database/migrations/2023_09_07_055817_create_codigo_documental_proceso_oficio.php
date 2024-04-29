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
        Schema::create('coddocumprocesooficio', function (Blueprint $table) {
            $table->bigIncrements('codopoid')->unsigned()->comment('Identificador de la tabla codigo documental proceso oficio');
            $table->bigInteger('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario que crea el documento');
            $table->smallInteger('tipsalid')->unsigned()->comment('Identificador de la tabla tipo saludo');
            $table->smallInteger('tipdesid')->unsigned()->comment('Identificador de la tabla tipo despedida');
            $table->string('codopoconsecutivo', 4)->comment('Consecutivo de la oficio');
            $table->string('codoposigla', 3)->comment('Sigla de la dependencia productora de la oficio');
            $table->year('codopoanio', 4)->comment('Año en el cual se crea la oficio');
            $table->string('codopotitulo', 80)->nullable()->comment('Título de la persona a la que va dirigido el ofico');  
            $table->string('codopociudad', 80)->nullable()->comment('Ciudad a la que va dirigido el oficio');   
            $table->string('codopoempresa', 80)->nullable()->comment('Nombre de la persona o empresa a la que va dirigido el oficio'); 
            $table->string('codopodireccion', 80)->nullable()->comment('direción de la persona o empresa a la que va dirigido el oficio'); 
            $table->string('codopotelefono', 20)->nullable()->comment('Telefono de la persona o empresa a la que va dirigido el oficio'); 
            $table->boolean('codoporesponderadicado')->default(false)->comment('Determina si se esta respondiendo radicados en el oficio');
            $table->timestamps();
            $table->unique(['codopoconsecutivo','codoposigla','codopoanio'],'uk_coddocumprocesooficio');
            $table->foreign('codoprid')->references('codoprid')->on('codigodocumentalproceso')->onUpdate('cascade')->index('fk_codoprcodopo'); 
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuacodopo'); 
            $table->foreign('tipsalid')->references('tipsalid')->on('tiposaludo')->onUpdate('cascade')->index('fk_tipsalcodopo');
            $table->foreign('tipdesid')->references('tipdesid')->on('tipodespedida')->onUpdate('cascade')->index('fk_tipdescodopo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigo_documental_proceso_oficio');
    }
};
