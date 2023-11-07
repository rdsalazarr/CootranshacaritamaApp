<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoServicioVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tiposerviciovehiculo')->insert([
            'tiseveid'     => 'B',
            'tisevenombre' => 'Básico'
        ]);

        DB::table('tiposerviciovehiculo')->insert([
            'tiseveid'     => 'C',
            'tisevenombre' => 'Común'
        ]);

        DB::table('tiposerviciovehiculo')->insert([
            'tiseveid'     => 'E',
            'tisevenombre' => 'Especial'
        ]);

        DB::table('tiposerviciovehiculo')->insert([
            'tiseveid'     => 'CR',
            'tisevenombre' => 'Corriente'
        ]);

        DB::table('tiposerviciovehiculo')->insert([
            'tiseveid'     => 'SS',
            'tisevenombre' => 'Sin servicio'
        ]);
    }
}
