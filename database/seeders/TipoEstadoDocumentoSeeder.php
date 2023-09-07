<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstadoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoestadodocumento')->insert([
            'tiesdoid'     => '1',
            'tiesdonombre' => 'Inicial'
        ]);

        DB::table('tipoestadodocumento')->insert([ 
            'tiesdoid'     => '2',
            'tiesdonombre' => 'Solicitar firma'
        ]);

        DB::table('tipoestadodocumento')->insert([
            'tiesdoid'     => '3',
            'tiesdonombre' => 'Anular la solicitud de firma'
        ]);

        DB::table('tipoestadodocumento')->insert([ 
            'tiesdoid'     => '4',
            'tiesdonombre' => 'Documento firmado'
        ]);

        DB::table('tipoestadodocumento')->insert([ 
            'tiesdoid'     => '5',
            'tiesdonombre' => 'Documento sellado'
        ]);

        DB::table('tipoestadodocumento')->insert([
            'tiesdoid'     => '6',
            'tiesdonombre' => 'Documento Radicado'
        ]);

        DB::table('tipoestadodocumento')->insert([
            'tiesdoid'     => '7',
            'tiesdonombre' => 'Documento compartido'
        ]);

        DB::table('tipoestadodocumento')->insert([
            'tiesdoid'     => '8',
            'tiesdonombre' => 'Documento recibido'
        ]);

        DB::table('tipoestadodocumento')->insert([
            'tiesdoid'     => '9',
            'tiesdonombre' => 'Solicitar anulación del documento'
        ]);

        DB::table('tipoestadodocumento')->insert([
            'tiesdoid'     => '10',
            'tiesdonombre' => 'Documento anulado'
        ]);
    }
}
