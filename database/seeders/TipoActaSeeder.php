<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoActaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoacta')->insert([
            'tipactid'     => '1',
            'tipactnombre' => 'Ordinaria'
        ]);

        DB::table('tipoacta')->insert([
            'tipactid'     => '2',
            'tipactnombre' => 'Extra Ordinaria'
        ]);
    }
}
