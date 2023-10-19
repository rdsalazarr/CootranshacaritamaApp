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
        Schema::create('tipocombustiblevehiculo', function (Blueprint $table) {
            $table->tinyInteger('ticovhid')->unsigned()->comment('Identificador de la tabla tipo combustible vehículo');
            $table->string('ticovhnombre', 30)->comment('Nombre del tipo combustible vehículo');;
            $table->primary('ticovhid')->index('pk_tiesve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipocombustiblevehiculo');
    }
};
