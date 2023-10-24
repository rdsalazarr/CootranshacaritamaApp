<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoConductorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoconductor')->insert([
            'tipconid'     => 'P',
            'tipconnombre' => 'PRINCIPAL'
        ]);

        DB::table('tipoconductor')->insert([
            'tipconid'     => 'S',
            'tipconnombre' => 'SUPLENTE'
        ]);

        DB::table('tipoconductor')->insert([
            'tipconid'     => 'R',
            'tipconnombre' => 'RELEVADOR'
        ]);
    }
}