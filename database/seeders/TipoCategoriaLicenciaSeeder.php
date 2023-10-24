<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoCategoriaLicenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {       
        DB::table('tipocategorialicencia')->insert([
            'ticaliid'     => 'A1',
            'ticalinombre' => 'A1'
        ]);

        DB::table('tipocategorialicencia')->insert([
            'ticaliid'     => 'A2',
            'ticalinombre' => 'A2'
        ]);

        DB::table('tipocategorialicencia')->insert([
            'ticaliid'     => 'B1',
            'ticalinombre' => 'B1'
        ]);

        DB::table('tipocategorialicencia')->insert([
            'ticaliid'     => 'B2',
            'ticalinombre' => 'B2'
        ]);

        DB::table('tipocategorialicencia')->insert([
            'ticaliid'     => 'C1',
            'ticalinombre' => 'C1'
        ]);

        DB::table('tipocategorialicencia')->insert([
            'ticaliid'     => 'C2',
            'ticalinombre' => 'C2'
        ]);

        DB::table('tipocategorialicencia')->insert([
            'ticaliid'     => 'C3',
            'ticalinombre' => 'C3'
        ]); 
    }
}