<?php

namespace App\Http\Controllers\Admin\Procesos;

use App\Console\Commands\Notificacion;
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
                                    DB::raw("IF(proautfechaejecucion >= CURDATE(), 'SI', 'NO') as esFechaActual"),
                                    DB::raw("IF(proautfechaejecucion < DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'SI', 'NO') as esFechaAnterior"),
                                    DB::raw("CURDATE() as fechaActual"),
                                    DB::raw("(SELECT DATE_SUB(CURDATE(), INTERVAL 1 DAY)) as fechaAnterior"))
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
            $clases = [
                'Dia'          => new Dia(),
                'Noche'        => new Noche(),
                'Notificacion' => new Notificacion(),
            ];

            $procesoautomatico = DB::table('procesoautomatico')
                                        ->select('proautmetodo','proautclasephp',
                                        DB::raw("IF(proautfechaejecucion > CURDATE(), 'SI', 'NO') as esFechaActualMayor"))
                                        ->where('proautid', $request->codigo)->first();
            if ($procesoautomatico) {
                $fechaActualMayor = $procesoautomatico->esFechaActualMayor;
                $nombreClase      = $procesoautomatico->proautclasephp;
                $nombreMetodo     = $procesoautomatico->proautmetodo;

                if($fechaActualMayor === 'SI'){
                    return response()->json(['success' => false, 'message' => 'No se puede ejecutar un proceso para una fecha posterior a la actual']);
                }

                if (array_key_exists($nombreClase, $clases)) {
                    $instanciaClase  = $clases[$nombreClase];
                    $datosRetornados = $instanciaClase::$nombreMetodo(true);//Tener en cuenta que el metodo y el tipo de proceso esta dinamico
                    $success         = $datosRetornados['success'];
                    $message         = $datosRetornados['message'];
                } else {
                    $success = false;
                    $message = "La clase $nombreClase no fue encontrada";
                }
            }else{
                $success = false;
                $message = "El proceso automático no fue encontrado";
            }
        	return response()->json(['success' => $success, 'message' => $message]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}