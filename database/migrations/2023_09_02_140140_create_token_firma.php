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
        Schema::create('tokenfirma', function (Blueprint $table) {
            $table->bigIncrements('tokfirid')->unsigned()->comment('Identificador de la tabla token firma');
            $table->string('tokfirtoken', 20)->unique('uk_tokenfirma')->comment('Token creado aleatoriamente para validar la firma');
            $table->datetime('tokfirfechahoranotificacion')->comment('Fecha y hora de la cual se envio la notifiación'); 
            $table->datetime('tokfirfechahoramaxvalidez')->comment('Fecha y hora maxima de validez del token'); 
            $table->string('tokfirmsjcorreo', 500)->nullable()->comment('Contendio de la información enviada al correo');
            $table->string('tokfirmsjcelular', 200)->nullable()->comment('Contendio de la información enviada al celular');
            $table->boolean('tokfirutilizado')->default(false)->comment('Determina si el token fue utilizado'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokenfirma');
    }
};
