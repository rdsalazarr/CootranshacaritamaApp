<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoCajaUbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '1',
            'ticaubnombre' => 'Caja uno'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '2',
            'ticaubnombre' => 'Caja dos'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '3',
            'ticaubnombre' => 'Caja tres'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '4',
            'ticaubnombre' => 'Caja cuatro'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '5',
            'ticaubnombre' => 'Caja cinco'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '6',
            'ticaubnombre' => 'Caja seis'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '7',
            'ticaubnombre' => 'Caja siete'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '8',
            'ticaubnombre' => 'Caja ocho'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '9',
            'ticaubnombre' => 'Caja nueve'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '10',
            'ticaubnombre' => 'Caja diez'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '11',
            'ticaubnombre' => 'Caja once'
        ]);

        DB::table('tipocajaubicacion')->insert([
            'ticaubid'     => '12',
            'ticaubnombre' => 'Caja doce'
        ]);
    }
}