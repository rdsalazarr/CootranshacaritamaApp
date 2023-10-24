<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoPersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipopersona')->insert([
            'tipperid'     => 'E',
            'tippernombre' => 'Empleado'
        ]);

        DB::table('tipopersona')->insert([
            'tipperid'     => 'A',
            'tippernombre' => 'Asociado'
        ]);

        DB::table('tipopersona')->insert([
            'tipperid'     => 'C',
            'tippernombre' => 'Conductor'
        ]);

        DB::table('tipopersona')->insert([
            'tipperid'     => 'X',
            'tippernombre' => 'Externo'
        ]);
    }
}
