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
            $table->integer('persid')->unsigned()->comment('Identificador de la tabla persona');
            $table->tinyInteger('tipideid')->unsigned()->comment('Identificador del tipo de identificación');
            $table->string('usuadocumento', 15)->comment('Documento del usuario');
            $table->string('usuanombre', 50)->comment('Nombre del usuario');
            $table->string('usuaapellidos', 50)->comment('Apellidos del usuario');
            $table->string('usuaemail', 80)->unique('uk_usuario')->comment('Correo del usuario');
            $table->string('usuanick', 20)->unique('uk_usuario1')->comment('Nick del usuario');
            $table->string('password')->comment('Password del usuario');
            $table->boolean('usuacambiarpassword')->default(true)->comment('Determina si el usuario debe cambar la contraseña para poder inciar sesión');
            $table->boolean('usuabloqueado')->default(false)->comment('Determina si el usuario esta activo');
            $table->boolean('usuaactivo')->default(true)->comment('Determina si el usuario esta activo');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('tipideid')->references('tipideid')->on('tipoidentificacion')->onUpdate('cascade')->index('fk_tipideusua');
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
