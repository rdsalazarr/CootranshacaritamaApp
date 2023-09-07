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
        Schema::create('tipoacta', function (Blueprint $table) {
            $table->tinyInteger('tipactid')->unsigned()->comment('Identificador del tipo de acta');
            $table->string('tipactnombre', 30)->comment('Nombre del tipo de acta');
            $table->primary('tipactid')->index('pk_tipact');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipoacta');
    }
};
