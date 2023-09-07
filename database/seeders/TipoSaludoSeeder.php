<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoSaludoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '1',
            'tipsalnombre' => 'Apreciado señor,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '2',
            'tipsalnombre' => 'Apreciada señora,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '3',
            'tipsalnombre' => 'Apreciado proveedor,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '4',
            'tipsalnombre' => 'Cordial saludo,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '5',
            'tipsalnombre' => 'Estimado señor,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '6',
            'tipsalnombre' => 'Estimada señora,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '7',
            'tipsalnombre' => 'Estimado cliente,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '8',
            'tipsalnombre' => 'Estimado consultante,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '9',
            'tipsalnombre' => 'Distinguido señor,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '10',
            'tipsalnombre' => 'Distinguida señora,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '11',
            'tipsalnombre' => 'Distinguidos señores,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '19',
            'tipsalnombre' => 'Notable señor,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '12',
            'tipsalnombre' => 'Notable señora,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '13',
            'tipsalnombre' => 'Notables señores,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '14',
            'tipsalnombre' => 'Respetable señor,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '15',
            'tipsalnombre' => 'Respetable señora,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '16',
            'tipsalnombre' => 'Respetables señores,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '17',
            'tipsalnombre' => 'Amable señor,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tiposaludo')->insert([
            'tipsalid'     => '18',
            'tipsalnombre' => 'Amable señora,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);
    }
}
