<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoConvenioServicioEspecialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoconvenioservicioespecial')->insert([
            'ticossid'     => 'CV',
            'ticossnombre' => 'Convenio'
        ]);

        DB::table('tipoconvenioservicioespecial')->insert([
            'ticossid'     => 'CS',
            'ticossnombre' => 'Consorcio'
        ]);

        DB::table('tipoconvenioservicioespecial')->insert([
            'ticossid'     => 'UT',
            'ticossnombre' => 'Union temporal'
        ]);

        DB::table('tipoconvenioservicioespecial')->insert([
            'ticossid'     => 'NA',
            'ticossnombre' => 'No aplica'
        ]);
    }
}
