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
        Schema::create('persona', function (Blueprint $table) {
            $table->increments('persid')->unsigned()->comment('Identificador de la tabla persona');
            $table->smallInteger('carlabid')->unsigned()->comment('Identificador del cargo laboral');
            $table->tinyInteger('tipideid')->unsigned()->comment('Identificador del tipo de identificación');
            $table->string('tipperid', 2)->comment('Identificador del tipo de persona');
            $table->tinyInteger('persdepaidnacimiento')->unsigned()->nullable()->comment('Identificador del departamento de nacimiento del documento');
            $table->smallInteger('persmuniidnacimiento')->unsigned()->nullable()->comment('Identificador del municipio de nacimiento del documento'); 
            $table->tinyInteger('persdepaidexpedicion')->unsigned()->nullable()->comment('Identificador del departamento de expedición del documento');
            $table->smallInteger('persmuniidexpedicion')->unsigned()->nullable()->comment('Identificador del municipio de expedición del documento'); 
            $table->string('persdocumento', 15)->comment('Número de documento de la persona');
            $table->string('persprimernombre', 100)->comment('Primer nombre de la persona');
            $table->string('perssegundonombre', 40)->nullable()->comment('Segundo nombre de la persona');
            $table->string('persprimerapellido', 40)->nullable()->comment('Primer apellido de la persona');
            $table->string('perssegundoapellido', 40)->nullable()->comment('Segundo apellido de la persona');
            $table->date('persfechanacimiento')->nullable()->comment('Fecha de nacimiento de la persona');
            $table->string('persdireccion',100)->comment('Determina el genero de la persona');
            $table->string('perscorreoelectronico', 80)->nullable()->comment('Correo electrónico de la persona');
            $table->date('persfechadexpedicion')->nullable()->comment('Fecha de nacimiento de la persona');
            $table->string('persnumerotelefonofijo', 20)->nullable()->comment('Número de teléfono fijo de la persona');
            $table->string('persnumerocelular', 20)->nullable()->comment('Número de teléfono celular de la persona');
            $table->string('persgenero', 1)->comment('Determina el genero de la persona');
            $table->string('persrutafoto', 100)->nullable()->comment('Ruta de la foto de la persona');
            $table->string('persrutafirma', 100)->nullable()->comment('Ruta de la firma digital de la persona para la gestión documental');
            $table->boolean('perstienefirmadigital')->default(false)->comment('Determina si la persona tiene firma digital');
            $table->string('persclavecertificado', 20)->nullable()->comment('Clave del certificado digital');
            $table->string('persrutacrt', 500)->nullable()->comment('Ruta de certificado digital con extensión crt');
            $table->string('persrutapem', 500)->nullable()->comment('Ruta de certificado digital con extensión pem');
            $table->boolean('persactiva')->default(true)->comment('Determina si la persona se encuentra activa');
            $table->timestamps();
            $table->unique(['tipideid','persdocumento'],'uk_personatipoidentificacion');
            $table->foreign('carlabid')->references('carlabid')->on('cargolaboral')->onUpdate('cascade')->index('fk_carglabpers');
            $table->foreign('tipideid')->references('tipideid')->on('tipoidentificacion')->onUpdate('cascade')->index('fk_tipidepers');
            $table->foreign('tipperid')->references('tipperid')->on('tipopersona')->onUpdate('cascade')->index('fk_tipperpers');
            $table->foreign('persdepaidnacimiento')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depapersnac');
            $table->foreign('persmuniidnacimiento')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munipersnac');
            $table->foreign('persdepaidexpedicion')->references('depaid')->on('departamento')->onUpdate('cascade')->index('fk_depapersexp');
            $table->foreign('persmuniidexpedicion')->references('muniid')->on('municipio')->onUpdate('cascade')->index('fk_munipersexp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
