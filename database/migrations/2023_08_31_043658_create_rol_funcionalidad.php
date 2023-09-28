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
        Schema::create('rolfuncionalidad', function (Blueprint $table) {
            $table->mediumIncrements('rolfunid')->comment('Identificador de la tabla rol funcionalidad');
            $table->smallInteger('rolfunrolid')->unsigned()->comment('Identificador del rol');
            $table->smallInteger('rolfunfuncid')->unsigned()->comment('Identificador de la funcionalidad');

            $table->foreign('rolfunrolid')->references('rolid')->on('rol')->onUpdate('cascade')->index('fk_rolrolfun');
            $table->foreign('rolfunfuncid')->references('funcid')->on('funcionalidad')->onUpdate('cascade')->index('fk_funcrolfun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rolfuncionalidad');
    }
};
