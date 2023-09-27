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
        Schema::create('usuariorol', function (Blueprint $table) {
            $table->increments('usurolid')->index('pk_usurol')->comment('Identificador de la tabla usuario rol');
            $table->smallInteger('usurolusuaid')->unsigned()->comment('Identificador del usuario');
            $table->smallInteger('usurolrolid')->unsigned()->comment('Identificador del rol');

            $table->foreign('usurolusuaid')->references('usuaid')->on('usuario')->onUpdate('cascade')->index('fk_usuausurol'); 
            $table->foreign('usurolrolid')->references('rolid')->on('rol')->onUpdate('cascade')->index('fk_rolusurol'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuariorol');
    }
};
