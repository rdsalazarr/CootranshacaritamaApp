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
        Schema::create('informacionconfiguracioncorreo', function (Blueprint $table) {
            $table->tinyInteger('incocoid')->unsigned()->comment('Identificador de la tabla información configuración del correo');
            $table->string('incocohost', 50)->comment('Host para el cual se permite enviar el correo');
            $table->string('incocousuario', 80)->comment('Usuario o correo con el cual se va autenticar para enviar los correos en el sistema'); 
            $table->string('incococlave', 20)->comment('Clave del correo para acceder a la plataforma');
            $table->string('incococlaveapi', 20)->comment('Clave de la api para autenticar y poder enviar el corro');
            $table->string('incocopuerto', 4)->comment('Puerto por el cual se envia el correo');
            $table->timestamps();
            $table->primary('incocoid')->index('pk_incoco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacionconfiguracioncorreo');
    }
};
