<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEstadoConductorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoestadoconductor')->insert([
            'tiescoid'     => 'A',
            'tiesconombre' => 'Activo'
        ]);

        DB::table('tipoestadoconductor')->insert([
            'tiescoid'     => 'S',
            'tiesconombre' => 'Sancionado'
        ]);

        DB::table('tipoestadoconductor')->insert([
            'tiescoid'     => 'I',
            'tiesconombre' => 'Inactivo'
        ]);
    }
}
