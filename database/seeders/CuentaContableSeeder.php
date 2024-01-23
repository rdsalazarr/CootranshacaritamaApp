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

        /*DB::table('cuentacontable')->insert([
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
            'cueconcodigo'     => '120000',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '4',
            'cueconnombre'     => 'CXP MENSUALIDADES TOTAL',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120001',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);*/

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
            'cueconcodigo'     => '120002',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '7',
            'cueconnombre'     => 'CXP PAGO SANCIÓN',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120005',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);


    }
}
