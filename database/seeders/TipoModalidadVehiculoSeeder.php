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
            'timoveid'            => 'E',
            'timovenombre'        => 'ESPECIAL',
            'timovetienedespacho' => '0'
        ]);
        
        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => 'I',
            'timovenombre'        => 'INTERMUNICIPAL',
            'timovetienedespacho' => '1'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => 'C',
            'timovenombre'        => 'COLECTIVO',
            'timovetienedespacho' => '0'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => 'M',
            'timovenombre'        => 'MIXTO',
            'timovetienedespacho' => '1'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'            => 'P',
            'timovenombre'        => 'PRIVADO',
            'timovetienedespacho' => '0'
        ]);       
    }
}
