<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class CuentaContableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('cuentacontable')->insert([
            'cueconid'         => '1',
            'cueconnombre'     => 'CAJA',
            'cueconnaturaleza' => 'D',
            'cueconcodigo'     => '110001',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '2',
            'cueconnombre'     => 'BANCO',
            'cueconnaturaleza' => 'D',
            'cueconcodigo'     => '110002',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '3',
            'cueconnombre'     => 'CXP MENSUALIDADES',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120003',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '4',
            'cueconnombre'     => 'CXP MENSUALIDADES TOTAL',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120004',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '5',
            'cueconnombre'     => 'CXP PAGO CUOTA CRÉDITO',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120005',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '6',
            'cueconnombre'     => 'CXP PAGO CUOTA CRÉDITO TOTAL',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120006',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '7',
            'cueconnombre'     => 'CXP PAGO SANCIÓN',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120007',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);
        
        DB::table('cuentacontable')->insert([
            'cueconid'         => '8',
            'cueconnombre'     => 'CXP PAGO ENCOMIENDA',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120008',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);
        
        DB::table('cuentacontable')->insert([
            'cueconid'         => '9',
            'cueconnombre'     => 'CXP PAGO ENCOMIENDA CONTRAENTREGA',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120009',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '10',
            'cueconnombre'     => 'CXP PAGO DE TIQUETE',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120010',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '11',
            'cueconnombre'     => 'CXC DESEMBOLSOS',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120011',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '12',
            'cueconnombre'     => 'CXC PAGO MENSUALIDAD PARCIAL',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120012',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);
    }
}
