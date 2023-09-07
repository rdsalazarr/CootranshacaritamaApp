<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoMedioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipomedio')->insert([
            'tipmedid' => '1',
            'tipmednombre' => 'Impreso'
        ]);

        DB::table('tipomedio')->insert([
            'tipmedid' => '2',
            'tipmednombre' => 'Correo'
        ]);

        DB::table('tipomedio')->insert([
            'tipmedid' => '3',
            'tipmednombre' => 'Impreso / Correo'
        ]);

        DB::table('tipomedio')->insert([
            'tipmedid' => '4',
            'tipmednombre' => 'Fax'
        ]);

        DB::table('tipomedio')->insert([
            'tipmedid' => '5',
            'tipmednombre' => 'Otro'
        ]);
    }
}
