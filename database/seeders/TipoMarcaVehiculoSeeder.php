<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoMarcaVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '1',
            'timavenombre' => 'NISSAN',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '2',
            'timavenombre' => 'CHEVROLET',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '3',
            'timavenombre' => 'DAIHATSU',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '4',
            'timavenombre' => 'RENAULT',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '5',
            'timavenombre' => 'HYUNDAI',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '6',
            'timavenombre' => 'DAEWOO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '7',
            'timavenombre' => 'FORD',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '8',
            'timavenombre' => 'KIA',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '9',
            'timavenombre' => 'SUZUKI',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '10',
            'timavenombre' => 'MITSUBISHI',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '11',
            'timavenombre' => 'DFSK',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '12',
            'timavenombre' => 'MERCEDES BENZ',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '13',
            'timavenombre' => 'JAC',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '14',
            'timavenombre' => 'WILLYS',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '15',
            'timavenombre' => 'AGRALE',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '16',
            'timavenombre' => 'DODGE',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '17',
            'timavenombre' => 'INTERNATIONAL',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '18',
            'timavenombre' => 'HINO',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '19',
            'timavenombre' => 'VOLKSWAGEN',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '20',
            'timavenombre' => 'MAZDA',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '21',
            'timavenombre' => 'SUSUKI',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '22',
            'timavenombre' => 'JEEP',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipomarcavehiculo')->insert([
            'timaveid'     => '23',
            'timavenombre' => 'FOTON',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);
    }
}