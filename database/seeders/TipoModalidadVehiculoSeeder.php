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
            'timoveid'                      => 'E',
            'timovenombre'                  => 'ESPECIAL',
            'timovecuotasotenimiento'       => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1'
        ]);
        
        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'                      => 'I',
            'timovenombre'                  => 'INTERMUNICIPAL',
            'timovecuotasotenimiento'       => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'                      => 'C',
            'timovenombre'                  => 'COLECTIVO',
            'timovecuotasotenimiento'       => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1'
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'                      => 'M',
            'timovenombre'                  => 'MIXTO',
            'timovecuotasotenimiento'       => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1'
        ]);
    }
}