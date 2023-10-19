<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoCombustibleVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipocombustiblevehiculo')->insert([
            'ticovhid'     => '1',
            'ticovhnombre' => 'ACPM'
        ]);

        DB::table('tipocombustiblevehiculo')->insert([
            'ticovhid'     => '2',
            'ticovhnombre' => 'GASOLINA'
        ]);

        DB::table('tipocombustiblevehiculo')->insert([
            'ticovhid'     => '3',
            'ticovhnombre' => 'GAS'
        ]);

        DB::table('tipocombustiblevehiculo')->insert([
            'ticovhid'     => '4',
            'ticovhnombre' => 'HIBRIDO'
        ]);
    }
}
