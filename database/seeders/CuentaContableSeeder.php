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
            'cueconcodigo'     => '110000',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'         => '2',
            'cueconnombre'     => 'CXP MENSUALIDADES',
            'cueconnaturaleza' => 'C',
            'cueconcodigo'     => '120000',
            'created_at'       => $fechaHoraActual,
            'updated_at'       => $fechaHoraActual,
        ]);

    }
}