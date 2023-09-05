<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDocumental;
use DB;

class TipoDocumentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipodocumental')->insert([
            'tipdocid'     => '1',
            'tipdoccodigo' => 'A',
            'tipdocnombre' => 'Acta'
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '2',
            'tipdoccodigo' => 'B',
            'tipdocnombre' => 'Certificado'
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '3',
            'tipdoccodigo' => 'C',
            'tipdocnombre' => 'Circular'
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '4',
            'tipdoccodigo' => 'H',
            'tipdocnombre' => 'Citación'
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '5',
            'tipdoccodigo' => 'T',
            'tipdocnombre' => 'Constancia'
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '6',
            'tipdoccodigo' => 'O',
            'tipdocnombre' => 'Oficio'
        ]);

        DB::table('tipodocumental')->insert([
            'tipdocid'     => '7',
            'tipdoccodigo' => 'R',
            'tipdocnombre' => 'Resolución',
            'tipdocactivo' => '0'
        ]); 
    }
}
