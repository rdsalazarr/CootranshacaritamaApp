<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDestino;
use DB;

class TipoDestinoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipodestino')->insert([
            'tipdetid'     => '1',
            'tipdetnombre' => 'Interno'
        ]);

        DB::table('tipodestino')->insert([
            'tipdetid'     => '2',
            'tipdetnombre' => 'Externo'
        ]);

        DB::table('tipodestino')->insert([
            'tipdetid'     => '3',
            'tipdetnombre' => 'Interno / Externo'
        ]);
    }
}
