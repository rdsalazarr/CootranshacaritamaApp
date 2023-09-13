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
        Schema::create('historialcontrasena', function (Blueprint $table) {
            $table->bigIncrements('hisconid')->unsigned()->comment('Identificador de la tabla historial de contrasena');
            $table->smallInteger('usuaid')->unsigned()->comment('Identificador del usuario');
            $table->string('hisconpassword')->comment('Password del usuario utilizado');
            $table->timestamps();
            $table->foreign('usuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuahiscon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_contrasena');
    }
};
