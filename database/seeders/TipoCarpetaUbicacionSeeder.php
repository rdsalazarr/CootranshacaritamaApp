<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoCarpetaUbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '1',
            'ticrubnombre' => 'Carpeta uno'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '2',
            'ticrubnombre' => 'Carpeta dos'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '3',
            'ticrubnombre' => 'Carpeta tres'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '4',
            'ticrubnombre' => 'Carpeta cuatro'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '5',
            'ticrubnombre' => 'Carpeta cinco'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '6',
            'ticrubnombre' => 'Carpeta seis'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid' => '7',
            'ticrubnombre' => 'Carpeta siete'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '8',
            'ticrubnombre' => 'Carpeta ocho'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '9',
            'ticrubnombre' => 'Carpeta nueve'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '10',
            'ticrubnombre' => 'Carpeta diez'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '11',
            'ticrubnombre' => 'Carpeta once'
        ]);

        DB::table('tipocarpetaubicacion')->insert([
            'ticrubid'     => '12',
            'ticrubnombre' => 'Carpeta doce'
        ]);
    }
}
