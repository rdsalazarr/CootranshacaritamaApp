<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoModalidadVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        $fechaHoraActual = Carbon::now();

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'                      => 'E',
            'timovenombre'                  => 'ESPECIAL',
            'timovecuotasostenimiento'      => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual
        ]);
        
        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'                      => 'I',
            'timovenombre'                  => 'INTERMUNICIPAL',
            'timovecuotasostenimiento'      => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'                      => 'C',
            'timovenombre'                  => 'COLECTIVO',
            'timovecuotasostenimiento'      => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'                      => 'M',
            'timovenombre'                  => 'MIXTO',
            'timovecuotasostenimiento'      => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual
        ]);

        DB::table('tipomodalidadvehiculo')->insert([
            'timoveid'                      => 'U',
            'timovenombre'                  => 'URBANO',
            'timovecuotasostenimiento'      => '105000',
            'timovedescuentopagoanticipado' => '5',
            'timoverecargomora'             => '5',
            'timovetienedespacho'           => '1',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual
        ]);
    }
}