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
        Schema::create('usuario', function (Blueprint $table) {
            $table->smallIncrements('usuaid')->comment('Identificador de la tabla usuario');
            $table->integer('persid')->unsigned()->comment('Identificador de la persona');
            $table->string('usuanombre', 50)->comment('Nombre del usuario');
            $table->string('usuaapellidos', 50)->comment('Apellidos del usuario');
            $table->string('usuaemail', 80)->unique('uk_usuario')->comment('Correo del usuario');
            $table->string('usuanick', 20)->unique('uk_usuario1')->comment('Nick del usuario');
            $table->string('usuaalias', 50)->nullable()->comment('Alias para colocar como transcriptor del documento en la gestion documental');
            $table->string('password')->comment('Password del usuario');
            $table->boolean('usuacambiarpassword')->default(true)->comment('Determina si el usuario debe cambar la contraseña para poder inciar sesión');
            $table->boolean('usuabloqueado')->default(false)->comment('Determina si el usuario esta bloqueado');
            $table->boolean('usuaactivo')->default(true)->comment('Determina si el usuario esta activo');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('persid')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_persusua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
