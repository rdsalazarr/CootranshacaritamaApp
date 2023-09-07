<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoPersonaDocumentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();
        
        DB::table('tipopersonadocumental')->insert([
            'tipedoid' => '1',
            'tipedonombre' => 'El señor',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipopersonadocumental')->insert([
            'tipedoid' => '2',
            'tipedonombre' => 'El doctor',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipopersonadocumental')->insert([
            'tipedoid' => '3',
            'tipedonombre' => 'La doctora',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);
    }
}
