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
        Schema::create('dependencia', function (Blueprint $table) {
            $table->smallIncrements('depeid')->unsigned()->comment('Identificador de la tabla dependencia');
            $table->integer('depejefeid')->unsigned()->comment('Identificador del jefe de la dependencia');
            $table->string('depecodigo', 10)->unique('uk_dependencia1')->comment('Código de la dependencia');
            $table->string('depesigla', 3)->unique('uk_dependencia2')->comment('Sigla de la dependencia');
            $table->string('depenombre', 80)->comment('Nombre de la dependencia');
            $table->string('depecorreo', 80)->comment('Correo de la dependencia');
            $table->boolean('depeactiva')->default(false)->comment('Determina si la dependencia se encuentra activa');
            $table->timestamps();        
            $table->foreign('depejefeid')->references('persid')->on('persona')->onUpdate('cascade')->index('fk_persdepe'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dependencia');
    }
};
