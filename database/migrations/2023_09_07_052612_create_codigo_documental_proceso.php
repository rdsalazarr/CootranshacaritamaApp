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
        Schema::create('codigodocumentalproceso', function (Blueprint $table) {
            $table->bigIncrements('codoprid')->unsigned()->comment('Identificador de la tabla codigo documental proceso');
            $table->bigInteger('coddocid')->unsigned()->comment('Identificador de la tabla codigo documental');
            $table->tinyInteger('tiesdoid')->unsigned()->comment('Identificador de la tabla tipo estado documento');           
            $table->date('codoprfecha')->comment('Fecha en la cual se crea el documento');
            $table->string('codoprnombredirigido', 2000)->nullable()->comment('Nombre o nombres de la persona a quien va dirigido el documento');
            $table->string('codoprcargonombredirigido', 100)->nullable()->comment('Cargo de la persona a quien va dirigido el documento');
            $table->string('codoprasunto', 200)->nullable()->comment('Asunto por el cual se crea el documento o título de la resolución');
            $table->string('codoprcorreo', 1000)->nullable()->comment('Correo de la persona o personas a quien van dirigir el documento');
            $table->longText('codoprcontenido')->nullable()->comment('Contenido del documento');
            $table->boolean('codoprtieneanexo')->default(false)->comment('Determina si el documento tiene anexo');
            $table->string('codopranexonombre', 300)->nullable()->comment('Nombre del adjunto que se relaciona en el documento');
            $table->boolean('codoprtienecopia')->default(false)->comment('Determina si el documento tiene copia');
            $table->string('codoprcopianombre', 300)->nullable()->comment('Nombre de la persona a quien va dirigido el documento como copia');
            $table->string('codoprrutadocumento', 100)->nullable()->comment('Nombre de la ruta al sellar el documento');
            $table->boolean('codoprsolicitafirma')->default(false)->comment('Determina si el documento se le ha solicitado la firma');
            $table->boolean('codoprfirmado')->default(false)->comment('Determina si el documento ha sido firmado');
            $table->boolean('codoprsellado')->default(false)->comment('Determina si el documento esta sellado');
            $table->boolean('codoprradicado')->default(false)->comment('Determina si el documento fue radicado en ventanilla única');         
            $table->timestamps();
            $table->foreign('coddocid')->references('coddocid')->on('codigodocumental')->onUpdate('cascade')->index('fk_coddoccodopr'); 
            $table->foreign('tiesdoid')->references('tiesdoid')->on('tipoestadodocumento')->onUpdate('cascade')->index('fk_tiesdocodopr');           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigodocumentalproceso');
    }
};
