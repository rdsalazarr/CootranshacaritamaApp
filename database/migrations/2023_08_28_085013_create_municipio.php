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
        Schema::create('municipio', function (Blueprint $table) {
            $table->smallIncrements('muniid')->unsigned()->comment('Identificador de la tabla municipio');
            $table->tinyInteger('munidepaid')->unsigned()->comment('Identificador del departamento');
            $table->string('municodigo', 8)->unique('uk_municipio')->comment('Código del municipio');
            $table->string('muninombre', 80)->comment('Nombre del municipio');
            $table->boolean('munihacepresencia')->default(false)->comment('Determina si la entidad hace presencia en este municipio'); 
            $table->foreign('munidepaid')->references('depaid')->on('departamento')->index('fk_depamuni');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipio');
    }
};
