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
        Schema::create('informaciongeneralpdf', function (Blueprint $table) {
            $table->smallIncrements('ingpdfid')->unsigned()->comment('Identificador de la tabla información general PDF');
            $table->string('ingpdfnombre', 30)->unique('uk_informaciongeneralpdf')->comment('Nombre general para utilizar la consulta de la información en PDF');
            $table->string('ingpdftitulo', 100)->comment('Título de la información general del PDF');
            $table->longText('ingpdfcontenido')->comment('Contenido de la información que lleva PDF');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informaciongeneralpdf');
    }
};
