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
        Schema::create('tiporelacionlaboral', function (Blueprint $table) {
            $table->tinyInteger('tirelaid')->unsigned()->comment('Identificador del tipo de realación laboral');
            $table->string('tirelanombre', 30)->comment('Nombre del tipo de relación laboral');
            $table->primary('tirelaid')->index('pk_tirela');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiporelacionlaboral');
    }
};
