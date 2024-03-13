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
        Schema::create('companiaaseguradora', function (Blueprint $table) {
            $table->tinyInteger('comaseid')->unsigned()->comment('Identificador de la tabla compañia aseguradora');
            $table->string('comasenombre', 100)->comment('Nombre de la  compañia aseguradora');
            $table->string('comasenumeropoliza', 30)->comment('Número de póliza de la compañia aseguradora');
            $table->primary('comaseid')->index('pk_comase');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companiaaseguradora');
    }
};
