<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class TipoDespedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '1',
            'tipdesnombre' => 'Atentamente,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '2',
            'tipdesnombre' => 'Atentamente le saluda,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid' => '3',
            'tipdesnombre' => 'Atentamente se despide,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '4',
            'tipdesnombre' => 'Agradecidos por su amabilidad,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '5',
            'tipdesnombre' => 'Agradecidos por su atención,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '6',
            'tipdesnombre' => 'Cordialmente se despide,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid' => '7',
            'tipdesnombre' => 'Sin otro particular por el momento,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '8',
            'tipdesnombre' => 'Reiteramos nuestros mas cordiales saludos,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '9',
            'tipdesnombre' => 'Nuestra consideracion mas distinguida,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '10',
            'tipdesnombre' => 'En espera de sus noticias le saludamos,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '11',
            'tipdesnombre' => 'Un atento saludo,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '12',
            'tipdesnombre' => 'Agradeciendo su valiosa colaboración,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '13',
            'tipdesnombre' => 'En espera de una respuesta,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '14',
            'tipdesnombre' => 'Quedamos a su disposicion por cuanto puedan necesitar',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '15',
            'tipdesnombre' => 'Les quedamos muy agradecidos por su colaboración',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '16',
            'tipdesnombre' => 'Hasta otra oportunidad,',
            'created_at'   => $fechaHoraActual,
            'updated_at'   => $fechaHoraActual,
        ]);
    }
}