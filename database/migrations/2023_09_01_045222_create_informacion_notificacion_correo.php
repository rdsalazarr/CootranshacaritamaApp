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
        Schema::create('informacionnotificacioncorreo', function (Blueprint $table) {
            $table->tinyIncrements('innocoid')->comment('Identificador de la tabla informacion notificacion correo ');
            $table->string('innoconombre', 50)->unique('uk_infornotificacioncorreo')->comment('Nombre con el cual se consulta desde el sistema'); 
            $table->string('innocotitulo', 100)->comment('Título de la información que lleva notificación'); 
            $table->longText('innococontenido')->comment('Contenido de la información que lleva notificación');
            $table->boolean('innocoenviarpiepagina')->default(true)->comment('Determina si se va incluir el contenido de pie de pagina'); 
            $table->boolean('innocoenviarcopia')->default(true)->comment('Determina se se desea enviar copia al administrador'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacionnotificacioncorreo');
    }
};
