<?php

namespace App\Http\Controllers\Admin\Procesos;

use App\Console\Commands\Automaticos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class ProcesosAutomaticosController extends Controller
{
    public function index()
    { 
        try{
            $data = DB::table('procesoautomatico')->select('proautid','proautnombre','proautfechaejecucion',
                                    DB::raw("if(proauttipo= 'D' ,'Diurno', 'Nocturno') as tipoProceso"))
                                    ->orderBy('proautid')->get();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        } 
    }

    public function ejecutar(Request $request)
	{
        $this->validate(request(),['codigo' => 'required|numeric']);

        try {

            $datosRetornados = Automaticos::suspenderConductor(true);
            $success         = $datosRetornados['success'];
            $message         = $datosRetornados['message'];

        	return response()->json(['success' => $success, 'message' => $message]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}