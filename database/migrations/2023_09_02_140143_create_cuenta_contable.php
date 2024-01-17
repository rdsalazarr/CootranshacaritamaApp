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
        Schema::create('cuentacontable', function (Blueprint $table) {
            $table->increments('cueconid')->unsigned()->comment('Identificador de la tabla cuenta contable');
            $table->string('cueconnombre', 200)->comment('Nombre de la cuenta contable');
            $table->string('cueconnaturaleza', 1)->default('D')->comment('Naturaleza de la cuenta contable');
            $table->string('cueconcodigo', 20)->comment('Codigo contable de la cuenta contable');
            $table->boolean('cueconactiva')->default(true)->comment('Determina si la cuenta contable se encuentra activa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentacontable');
    }
};
