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
        Schema::create('intentosfallidos', function (Blueprint $table) {
            $table->bigIncrements('intfalid')->unsigned()->comment('Identificador de la tabla intentos fallidos'); 
            $table->string('intfalusurio', 20)->comment('Usuario que accede al sistema');
            $table->string('intfalipacceso', 20)->comment('Ip de la cual accede el usuario al sistema');
            $table->dateTime('intfalfecha')->comment('Fecha y hora de ingreso al sistema'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intentosfallidos');
    }
};
