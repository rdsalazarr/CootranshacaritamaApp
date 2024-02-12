<?php

namespace App\Http\Controllers\Admin\Procesos;

use App\Http\Controllers\Controller;
use App\Console\Commands\Noche;
use App\Console\Commands\Dia;
use Illuminate\Http\Request;
use Exception, DB;

class ProcesosAutomaticosController extends Controller
{
    public function index(Request $request)
    { 
        $this->validate(request(),['tipo' => 'required']);

        try{
            $data = DB::table('procesoautomatico')
                                ->select('proautid', 'proautnombre', 'proautfechaejecucion',
                                    DB::raw("if(proauttipo = 'D', 'Diurno', 'Nocturno') as tipoProceso"),
                                    DB::raw("IF(proautfechaejecucion = CURDATE(), 'SI', 'NO') as esFechaActual"),
                                    DB::raw("CURDATE() as fechaActual"))
                                ->where('proauttipo', $request->tipo)
                                ->orderBy('proautid')
                                ->get();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        } 
    }

    public function ejecutar(Request $request)
	{
        $this->validate(request(),['codigo' => 'required|numeric']);

        try {
            $procesoautomatico = DB::table('procesoautomatico')->select('proautmetodo','proautclasephp')->where('proautid', $request->codigo)->first();
            if ($procesoautomatico) {
                $metodoPhp         = $procesoautomatico->proautmetodo;
                $clasePhp          = $procesoautomatico->proautclasephp;
                //dd($metodoPhp,  $clasePhp);
                //$datosRetornados   = Dia::suspenderConductor(true);
                $datosRetornados   = $metodoPhp::$clasePhp(true);//Tener en cuenta que el metodo y el tipo de proceso esta dinamico
                $success           = $datosRetornados['success'];
                $message           = $datosRetornados['message'];
            }else{
                $success = false;
                $message = "El proceso automático no fue encontrado.";
            }
        	return response()->json(['success' => $success, 'message' => $message]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}