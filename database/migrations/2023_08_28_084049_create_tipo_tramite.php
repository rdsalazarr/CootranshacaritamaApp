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
        Schema::create('tipotramite', function (Blueprint $table) {
            $table->tinyInteger('tiptraid')->unsigned()->comment('Identificador del tipo de trámite');
            $table->string('tiptranombre', 30)->comment('Nombre del tipo de trámite');
            $table->primary('tiptraid')->index('pk_tiptra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipotramite');
    }
};
