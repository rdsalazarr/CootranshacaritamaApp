<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class AgenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('agencia')->insert([
            'agenid'              => '101',
            'persidresponsable'   => '2',
            'agendepaid'          => '18',
            'agenmuniid'          => '804',
            'agennombre'          => 'PRINCIPAL',
            'agendireccion'       => 'Calle 7 a 56 211 la ondina vía a rio de oro',
            'agencorreo'          => 'cootranshacaritama@hotmail.com',
            'agentelefonocelular' => '3146034311',
            'agentelefonofijo'    => '5611012',
            'created_at'          => $fechaHoraActual,
            'updated_at'          => $fechaHoraActual,
        ]);
    }
}
