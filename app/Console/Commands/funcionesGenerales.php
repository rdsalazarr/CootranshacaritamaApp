<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;

class funcionesGenerales
{
    function consultarInfoEmpresa()
    {
        return DB::table('empresa as e')->select('e.emprcorreo',
                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreGerente"))
                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                        ->where('emprid', '1')->first();
    }

    function consultarFechaProceso($nombreProceso){
       $procesoautomatico = DB::table('procesoautomatico')->select('proautfechaejecucion')->where('proautnombre', $nombreProceso)->first();
       $fechaEjecucion    = Carbon::parse($procesoautomatico->proautfechaejecucion)->addDays(1);
       return $fechaEjecucion->format('Y-m-d');
    }

}