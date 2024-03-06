<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

class CuentaContableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fechaHoraActual = Carbon::now();

        DB::table('cuentacontable')->insert([
            'cueconid'          => '1',
            'cueconnombre'      => 'caja',
            'cuecondescripcion' => 'CAJA',
            'cueconnaturaleza'  => 'D',
            'cueconcodigo'      => '110001',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '2',
            'cueconnombre'      => 'banco',
            'cuecondescripcion' => 'BANCO',
            'cueconnaturaleza'  => 'D',
            'cueconcodigo'      => '110002',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '3',
            'cueconnombre'      => 'cxcDesembolso',
            'cuecondescripcion' => 'CXC DESEMBOLSOS',
            'cueconnaturaleza'  => 'D',
            'cueconcodigo'      => '120003',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '4',
            'cueconnombre'      => 'cxpMensualidades',
            'cuecondescripcion' => 'CXP MENSUALIDADES',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120004',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '5',
            'cueconnombre'      => 'cxpMensualidadTotal',
            'cuecondescripcion' => 'CXP MENSUALIDAD TOTAL',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120005',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '6',
            'cueconnombre'      => 'cxpPagoCuotaCredito',
            'cuecondescripcion' => 'CXP PAGO CUOTA CRÉDITO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120006',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '7',
            'cueconnombre'      => 'cxpPagoCreditoTotal',
            'cuecondescripcion' => 'CXP PAGO CRÉDITO TOTAL',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120007',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '8',
            'cueconnombre'      => 'cxpPagoSancion',
            'cuecondescripcion' => 'CXP PAGO SANCIÓN',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120008',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '9',
            'cueconnombre'      => 'cxpPagoMensualidadParcial',
            'cuecondescripcion' => 'CXC PAGO MENSUALIDAD PARCIAL',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120009',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '10',
            'cueconnombre'      => 'cxpPagoTiquete',
            'cuecondescripcion' => 'CXP PAGO DE TIQUETE',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120010',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '11',
            'cueconnombre'      => 'cxpFondoReposicion',
            'cuecondescripcion' => 'CXP FONDO REPOSICION',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120011',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '12',
            'cueconnombre'      => 'cxpPagoEstampilla',
            'cuecondescripcion' => 'CXP PAGO ESTAMIPILLA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120012',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '13',
            'cueconnombre'      => 'cxpPagoSeguro',
            'cuecondescripcion' => 'CXP PAGO SEGURO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120013',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '14',
            'cueconnombre'      => 'cxpDescuentoTiquete',
            'cuecondescripcion' => 'CXP DESCUENTO TIQUETE',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120014',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '15',
            'cueconnombre'      => 'cxpPagoEncomienda',
            'cuecondescripcion' => 'CXP PAGO ENCOMIENDA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120015',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '16',
            'cueconnombre'      => 'cxpComisionEmpresa',
            'cuecondescripcion' => 'CXP COMISIÓN EMPRESA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120016',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '17',
            'cueconnombre'      => 'cxpComisionAgencia',
            'cuecondescripcion' => 'CXP COMISIÓN AGENCIA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120017',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '18',
            'cueconnombre'      => 'cxpComisionVehiculo',
            'cuecondescripcion' => 'CXP COMISIÓN VEHÍCULO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '120018',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '19',
            'cueconnombre'      => 'cxpPagoEncomiendaDomicilio',
            'cuecondescripcion' => 'CXP PAGO ENCOMIENDA A DOMICILIO',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '1200019',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

        DB::table('cuentacontable')->insert([
            'cueconid'          => '20',
            'cueconnombre'      => 'cxpPagoEncomiendaContraentrega',
            'cuecondescripcion' => 'CXP PAGO ENCOMIENDA CONTRAENTREGA',
            'cueconnaturaleza'  => 'C',
            'cueconcodigo'      => '1200020',
            'created_at'        => $fechaHoraActual,
            'updated_at'        => $fechaHoraActual,
        ]);

    }
}