<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class CompaniaAseguradoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('companiaaseguradora')->insert([
            'comaseid'           => '1',
            'comasenombre'       => 'La Previsora SA',
            'comasenumeropoliza' => '7896321450',
            'created_at'         => $fechaHoraActual,
            'updated_at'         => $fechaHoraActual,
        ]);
    }
}
