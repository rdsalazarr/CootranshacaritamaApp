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
        Schema::create('tiposolicitud', function (Blueprint $table) {
            $table->string('tipsolid', 2)->comment('Identificador de la tabla tipo de solicitud');
            $table->string('tipsolnombre', 30)->comment('Nombre del tipo de solicitud');
            $table->primary('tipsolid')->index('pk_tipsol');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiposolicitud');
    }
};
