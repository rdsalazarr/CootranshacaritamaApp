<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoModalidadVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => '1',
            'timovenombre'        => 'TODAS',
            'timovetienedespacho' => '0'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => '2',
            'timovenombre'        => 'COLECTIVO',
            'timovetienedespacho' => '0'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => '3',
            'timovenombre'        => 'URBANO',
            'timovetienedespacho' => '0'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => '4',
            'timovenombre'        => 'INTERMUNICIPAL',
            'timovetienedespacho' => '1'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => '5',
            'timovenombre'        => 'MIXTO',
            'timovetienedespacho' => '1'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => '6',
            'timovenombre'        => 'PRIVADO',
            'timovetienedespacho' => '0'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => '7',
            'timovenombre'        => 'ESPECIAL',
            'timovetienedespacho' => '0'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => '8',
            'timovenombre'        => 'ESCOLAR',
            'timovetienedespacho' => '0'
        ]);
    }
}
