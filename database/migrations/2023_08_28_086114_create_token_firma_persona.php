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
        Schema::create('tokenfirmapersona', function (Blueprint $table) {
            $table->bigIncrements('tofipeid')->unsigned()->comment('Identificador de la tabla token firma');
            $table->integer('persid')->unsigned()->comment('Identificador de la persona');
            $table->string('tofipetoken', 20)->unique('uk_tokenfirma')->comment('Token creado aleatoriamente para validar la firma');
            $table->datetime('tofipefechahoranotificacion')->comment('Fecha y hora de la cual se envio la notifiación'); 
            $table->datetime('tofipefechahoramaxvalidez')->comment('Fecha y hora maxima de validez del token'); 
            $table->string('tofipemensajecorreo', 500)->nullable()->comment('Contendio de la información enviada al correo');
            $table->string('tofipemensajecelular', 200)->nullable()->comment('Contendio de la información enviada al celular');
            $table->boolean('tofipeutilizado')->default(false)->comment('Determina si el token fue utilizado'); 
            $table->timestamps();
            $table->foreign('persid')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_perstofipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokenfirmapersona');
    }
};
