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
        Schema::create('departamento', function (Blueprint $table) {
            $table->tinyInteger('depaid')->unsigned()->comment('Identificador de la tabla departamento');
            $table->string('depacodigo', 4)->unique('uk_departamento')->comment('Codigo del departamento');
            $table->string('depanombre', 80)->comment('Nombre del departamento');
            $table->boolean('depahacepresencia')->default(false)->comment('Determina si la entidad hace presencia en este departamento'); 
            $table->primary('depaid')->index('pk_depa'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departamento');
    }
};
