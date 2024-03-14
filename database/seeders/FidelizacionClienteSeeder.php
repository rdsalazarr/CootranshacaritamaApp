<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class FidelizacionClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('fidelizacioncliente')->insert([
            'fidcliid'                  => '1',
            'fidclivalorfidelizacion'   => '10000',
            'fidclivalorpunto'          => '100',
            'fidclipuntosminimoredimir' => '100',
            'created_at'                => $fechaHoraActual,
            'updated_at'                => $fechaHoraActual,
        ]);
    }
}
