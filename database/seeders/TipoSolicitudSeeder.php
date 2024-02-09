<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoSolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tiposolicitud')->insert([
            'tipsolid'     => 'P',
            'tipsolnombre' => 'PETICIÓN'
        ]);

        DB::table('tiposolicitud')->insert([
            'tipsolid'     => 'R',
            'tipsolnombre' => 'RECLAMO'
        ]);

        DB::table('tiposolicitud')->insert([
            'tipsolid'     => 'Q',
            'tipsolnombre' => 'QUEJA'
        ]);

        DB::table('tiposolicitud')->insert([
            'tipsolid'     => 'F',
            'tipsolnombre' => 'FELICITACIÓN'
        ]);
    }
}