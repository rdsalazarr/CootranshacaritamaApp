<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TipoContratoServicioEspecialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipocontratoservicioespecial')->insert([
            'ticoseid'     => 'ES',
            'ticosenombre' => 'Escolar'
        ]);

        DB::table('tipocontratoservicioespecial')->insert([
            'ticoseid'     => 'EM',
            'ticosenombre' => 'Empresarial'
        ]);

        DB::table('tipocontratoservicioespecial')->insert([
            'ticoseid'     => 'SA',
            'ticosenombre' => 'Salud'
        ]);

        DB::table('tipocontratoservicioespecial')->insert([
            'ticoseid'     => 'TU',
            'ticosenombre' => 'Turismo'
        ]);

        DB::table('tipocontratoservicioespecial')->insert([
            'ticoseid'     => 'GU',
            'ticosenombre' => 'Grupo de usuarios'
        ]);
    }
}