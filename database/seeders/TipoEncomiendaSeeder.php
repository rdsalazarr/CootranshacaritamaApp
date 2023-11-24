<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoEncomiendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipoencomienda')->insert([
            'tipencid'     => 'B',
            'tipencnombre' => 'Bolsa'
        ]);

        DB::table('tipoencomienda')->insert([
            'tipencid'     => 'L',
            'tipencnombre' => 'Bulto'
        ]);

        DB::table('tipoencomienda')->insert([
            'tipencid'     => 'C',
            'tipencnombre' => 'Caja'
        ]);

        DB::table('tipoencomienda')->insert([
            'tipencid'     => 'V',
            'tipencnombre' => 'Cava'
        ]);

        DB::table('tipoencomienda')->insert([
            'tipencid'     => 'E',
            'tipencnombre' => 'Equipaje'
        ]);

        DB::table('tipoencomienda')->insert([
            'tipencid'     => 'P',
            'tipencnombre' => 'Paquete'
        ]);

        DB::table('tipoencomienda')->insert([
            'tipencid'     => 'S',
            'tipencnombre' => 'Sobre'
        ]);
    }
}