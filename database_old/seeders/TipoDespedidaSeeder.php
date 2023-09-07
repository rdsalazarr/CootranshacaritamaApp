<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDespedida;
use DB;

class TipoDespedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipodespedida')->insert([
            'tipdesid'     => '1',
            'tipdesnombre' => 'Atentamente,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '2',
            'tipdesnombre' => 'Atentamente le saluda,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid' => '3',
            'tipdesnombre' => 'Atentamente se despide,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '4',
            'tipdesnombre' => 'Agradecidos por su amabilidad,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '5',
            'tipdesnombre' => 'Agradecidos por su atención,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '6',
            'tipdesnombre' => 'Cordialmente se despide,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid' => '7',
            'tipdesnombre' => 'Sin otro particular por el momento,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '8',
            'tipdesnombre' => 'Reiteramos nuestros mas cordiales saludos,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '9',
            'tipdesnombre' => 'Nuestra consideracion mas distinguida'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '10',
            'tipdesnombre' => 'En espera de sus noticias le saludamos,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '11',
            'tipdesnombre' => 'Un atento saludo,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '12',
            'tipdesnombre' => 'Agradeciendo su valiosa colaboración,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '13',
            'tipdesnombre' => 'En espera de una respuesta,'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '14',
            'tipdesnombre' => 'Quedamos a su disposicion por cuanto puedan necesitar'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '15',
            'tipdesnombre' => 'Les quedamos muy agradecidos por su colaboración'
        ]);

        DB::table('tipodespedida')->insert([
            'tipdesid'     => '16',
            'tipdesnombre' => 'Hasta otra oportunidad,'
        ]);
    }
}