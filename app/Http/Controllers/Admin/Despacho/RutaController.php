<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\TarifaTiquete;
use App\Http\Controllers\Controller;
use App\Models\Despacho\RutaNodo;
use App\Models\Despacho\Ruta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception, DB;

class RutaController extends Controller
{
    public function index(){
        $data = DB::table('ruta as r')
                    ->select('r.rutaid','r.depaidorigen','r.muniidorigen','r.depaiddestino','r.muniiddestino','r.rutaactiva','r.rutatienenodos',
                    'do.depanombre as nombreDeptoOrigen', 'mo.muninombre as nombreMunicipioOrigen',
                    'de.depanombre as nombreDeptoDestino', 'md.muninombre as nombreMunicipioDestino',
                    DB::raw("if(r.rutaactiva = 1 ,'Sí', 'No') as estado"))
                    ->join('departamento as do', 'do.depaid', '=', 'r.depaidorigen')
                    ->join('municipio as mo', function($join)
                    {
                        $join->on('mo.munidepaid', '=', 'r.depaidorigen');
                        $join->on('mo.muniid', '=', 'r.muniidorigen'); 
                    })
                    ->join('departamento as de', 'de.depaid', '=', 'r.depaiddestino') 
                    ->join('municipio as md', function($join)
                    {
                        $join->on('md.munidepaid', '=', 'r.depaiddestino');
                        $join->on('md.muniid', '=', 'r.muniiddestino'); 
                    })
                    ->orderBy('r.rutaid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required']);

        $departamentos  = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get();
        $municipios     = DB::table('municipio')->select('muniid','munidepaid','muninombre')->orderBy('muninombre')->get();
        $rutasNodo      = [];
        $tarifaTiquetes = [];

        if($request->tipo === 'U'){
            $rutasNodo      = DB::table('rutanodo')->select('rutnodid','muniid')->where('rutaid', $request->codigo)->get();
            $tarifaTiquetes = DB::table('tarifatiquete')
                                ->select('tartiqid','depaiddestino','muniiddestino','tartiqvalor','tartiqfondoreposicion')
                                ->where('rutaid', $request->codigo)->get();
        }

        return response()->json(["departamentos" => $departamentos, "municipios" => $municipios, "rutasNodo" => $rutasNodo, "tarifaTiquetes" => $tarifaTiquetes]);
    }

    public function salve(Request $request)
	{
        $rutaid  = $request->codigo;
        $ruta    = ($rutaid != 000) ? Ruta::findOrFail($rutaid) : new Ruta();

        $this->validate(request(),[
            'departamentoOrigen'   => 'required|numeric',
            'municipioOrigen'      => 'required|numeric',
            'departamentoDestino'  => 'required|numeric',
            'municipioDestino'     => 'required|numeric',
            'tieneNodos'           => 'required|numeric',
            'estado'               => 'required',
            'nodos'                => 'nullable|array|min:1',
            'tarifaTiquetes'       => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        try {

            $ruta->depaidorigen   = $request->departamentoOrigen;
            $ruta->muniidorigen   = $request->municipioOrigen;
            $ruta->depaiddestino  = $request->departamentoDestino;
            $ruta->muniiddestino  = $request->municipioDestino;
            $ruta->rutatienenodos = $request->tieneNodos;
            $ruta->rutaactiva     = $request->estado;
            $ruta->save();

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador de la ruta
				$rutaConsecutivo = Ruta::latest('rutaid')->first();
				$rutaid          = $rutaConsecutivo->rutaid;
			}

            if(count($request->nodos) > 0) {
                foreach($request->nodos as $dataNodo){
                    $identificador = $dataNodo['identificador'];
                    $municipio     = $dataNodo['municipio'];
                    $nodoEstado    = $dataNodo['estado'];
                    if($nodoEstado === 'I'){
                        $rutaNodo         = new RutaNodo();
                        $rutaNodo->rutaid = $rutaid;
                        $rutaNodo->muniid = $municipio;
                        $rutaNodo->save();
                    }else if($nodoEstado === 'D'){
                        $rutaNodo = RutaNodo::findOrFail($identificador);
                        $rutaNodo->delete();
                    }else{// Omitir
                    }
                }
            }

            foreach($request->tarifaTiquetes as $data){
				$identificador       = $data['identificador'];
				$departamentoDestino = $data['departamentoDestino'];
                $municipioDestino    = $data['municipioDestino'];
                $valorTiquete        = $data['valorTiquete'];
                $fondoReposicion     = $data['fondoReposicion'];
				$tarifaTiqueteEstado = $data['estado'];
				if($tarifaTiqueteEstado === 'I'){
					$tarifaTiquete                        = new TarifaTiquete();
					$tarifaTiquete->rutaid                = $rutaid;
					$tarifaTiquete->depaiddestino         = $departamentoDestino;
                    $tarifaTiquete->muniiddestino         = $municipioDestino;
                    $tarifaTiquete->tartiqvalor           = $valorTiquete;
                    $tarifaTiquete->tartiqfondoreposicion = $fondoReposicion;
					$tarifaTiquete->save();
				}else if($tarifaTiqueteEstado === 'D'){
					$tarifaTiquete = TarifaTiquete::findOrFail($identificador);
					$tarifaTiquete->delete();
				}else{// Omitir
				}
			}

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function destroy(Request $request)
	{
        $planillaruta = DB::table('planillaruta')->select('rutaid')->where('rutaid', $request->codigo)->first();
		if($planillaruta){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una dependencia del sistema']);
		}else{
			try {
				$ruta = Ruta::findOrFail($request->codigo);
				$ruta->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}