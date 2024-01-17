<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class CajaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('caja')->insert([
            'cajaid'     => '1',
            'cajanumero' => 'UNO'
        ]);

        DB::table('caja')->insert([
            'cajaid'     => '2',
            'cajanumero' => 'DOS'
        ]);

        DB::table('caja')->insert([
            'cajaid'     => '3',
            'cajanumero' => 'TRES'
        ]);

        DB::table('caja')->insert([
            'cajaid'     => '4',
            'cajanumero' => 'CUATRO'
        ]);
    }
}
