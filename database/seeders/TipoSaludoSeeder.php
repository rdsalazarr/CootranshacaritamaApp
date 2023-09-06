<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoSaludo;
use DB;

class TipoSaludoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tiposaludo')->insert([
            'tipsalid'     => '1',
            'tipsalnombre' => 'Apreciado señor,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '2',
            'tipsalnombre' => 'Apreciada señora,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '3',
            'tipsalnombre' => 'Apreciado proveedor,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '4',
            'tipsalnombre' => 'Cordial saludo,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '5',
            'tipsalnombre' => 'Estimado señor,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '6',
            'tipsalnombre' => 'Estimada señora'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '7',
            'tipsalnombre' => 'Estimado cliente,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '8',
            'tipsalnombre' => 'Estimado consultante,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '9',
            'tipsalnombre' => 'Distinguido señor,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '10',
            'tipsalnombre' => 'Distinguida señora,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '11',
            'tipsalnombre' => 'Distinguidos señores,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '19',
            'tipsalnombre' => 'Notable señor,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '12',
            'tipsalnombre' => 'Notable señora,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '13',
            'tipsalnombre' => 'Notables señores,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '14',
            'tipsalnombre' => 'Respetable señor,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '15',
            'tipsalnombre' => 'Respetable señora,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '16',
            'tipsalnombre' => 'Respetables señores,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '17',
            'tipsalnombre' => 'Amable señor,'
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '18',
            'tipsalnombre' => 'Amable señora,'
        ]);
    }
}
