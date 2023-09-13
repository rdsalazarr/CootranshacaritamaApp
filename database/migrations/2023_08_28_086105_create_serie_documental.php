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
        Schema::create('seriedocumental', function (Blueprint $table) {
            $table->smallIncrements('serdocid')->unsigned()->comment('Identificador de la tabla serie documental');
            $table->string('serdoccodigo', 3)->unique('uk_serie')->comment('Código de la serie');
            $table->string('serdocnombre', 80)->comment('Nombre de la serie');
            $table->smallInteger('serdoctiempoarchivogestion')->comment('Tiempo en el archivo de gestión');
            $table->smallInteger('serdoctiempoarchivocentral')->comment('Tiempo en el archivo central');
            $table->smallInteger('serdoctiempoarchivohistorico')->comment('Tiempo en el archivo historico');
            $table->boolean('serdocpermiteeliminar')->default(true)->comment('Determina si la serie se puede eliminar');
            $table->boolean('serdocactiva')->default(false)->comment('Determina si la serie esta activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seriedocumental');
    }
};
