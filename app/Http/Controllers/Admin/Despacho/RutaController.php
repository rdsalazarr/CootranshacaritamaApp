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
                    ->select('r.rutaid','r.rutadepaidorigen','r.rutamuniidorigen','r.rutadepaiddestino','r.rutamuniiddestino','r.rutaactiva','r.rutatienenodos',
                    'do.depanombre as nombreDeptoOrigen', 'mo.muninombre as nombreMunicipioOrigen',
                    'de.depanombre as nombreDeptoDestino', 'md.muninombre as nombreMunicipioDestino',
                    DB::raw("if(r.rutaactiva = 1 ,'Sí', 'No') as estado"))
                    ->join('departamento as do', 'do.depaid', '=', 'r.rutadepaidorigen')
                    ->join('municipio as mo', function($join)
                    {
                        $join->on('mo.munidepaid', '=', 'r.rutadepaidorigen');
                        $join->on('mo.muniid', '=', 'r.rutamuniidorigen'); 
                    })
                    ->join('departamento as de', 'de.depaid', '=', 'r.rutadepaiddestino') 
                    ->join('municipio as md', function($join)
                    {
                        $join->on('md.munidepaid', '=', 'r.rutadepaiddestino');
                        $join->on('md.muniid', '=', 'r.rutamuniiddestino'); 
                    })
                    ->orderBy('r.rutaid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required']);

        $departamentos  = DB::table('departamento')->select('depaid','depanombre')->where('depahacepresencia', true)->orderBy('depanombre')->get();
        $municipios     = DB::table('municipio')->select('muniid','munidepaid','muninombre')->where('munihacepresencia', true)->orderBy('muninombre')->get();
        $rutasNodo      = [];

        if($request->tipo === 'U'){
            $rutasNodo      = DB::table('rutanodo')->select('rutnodid','muniid')->where('rutaid', $request->codigo)->get();
        }

        return response()->json(["departamentos" => $departamentos, "municipios" => $municipios, "rutasNodo" => $rutasNodo]);
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
            'nodos'                => 'nullable|array|min:1'
        ]);

        DB::beginTransaction();
        try {

            $ruta->rutadepaidorigen  = $request->departamentoOrigen;
            $ruta->rutamuniidorigen  = $request->municipioOrigen;
            $ruta->rutadepaiddestino = $request->departamentoDestino;
            $ruta->rutamuniiddestino = $request->municipioDestino;
            $ruta->rutatienenodos    = $request->tieneNodos;
            $ruta->rutaactiva        = $request->estado;
            $ruta->save();

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador de la ruta
				$rutaConsecutivo = Ruta::latest('rutaid')->first();
				$rutaid          = $rutaConsecutivo->rutaid;
			}

            if($request->nodos !== null) {
                foreach($request->nodos as $dataNodo){
                    $identificador = $dataNodo['identificador'];
                    $municipio     = $dataNodo['municipioId'];
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

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function datosTiquete(Request $request)
	{
        $this->validate(request(),['codigo' => 'required']);


       /* $planillaRutas        = DB::table('planillaruta as pr')
                                ->select('pr.plarutid','r.rutadepaidorigen','r.rutamuniidorigen','r.rutadepaiddestino','r.rutamuniiddestino',
                                'mo.muninombre as municipioOrigen','md.muninombre as municipioDestino',
                                DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo,' - ', mo.muninombre,' - ', md.muninombre, ' - ', pr.plarutfechahorasalida) as nombreRuta"))
                                ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                                ->join('municipio as mo', function($join)
                                {
                                    $join->on('mo.munidepaid', '=', 'r.rutadepaidorigen');
                                    $join->on('mo.muniid', '=', 'r.rutamuniidorigen');
                                })
                                ->join('municipio as md', function($join)
                                {
                                    $join->on('md.munidepaid', '=', 'r.rutadepaiddestino');
                                    $join->on('md.muniid', '=', 'r.rutamuniiddestino');
                                })
                                ->where('pr.plarutdespachada', false)
                                ->get();**/


        $ruta           = DB::table('ruta as r')->select('md.muniid', 'md.muninombre')
                            ->join('municipio as md', function($join)
                            {
                                $join->on('md.munidepaid', '=', 'r.rutadepaiddestino');
                                $join->on('md.muniid', '=', 'r.rutamuniiddestino');
                            })
                            ->where('rutaid', $request->codigo)->first();

        $rutaNodos   = DB::table('rutanodo as rn')->select('m.muniid', 'm.muninombre')
                                ->join('municipio as m', 'm.muniid', '=', 'rn.muniid')
                                ->where('rn.rutaid', $request->codigo)
                                ->orderBy('m.muninombre')->get();

        $municipioRutas   = [];
        $municipioRutas[] = ['muniid' => $ruta->muniid, 'muninombre' => $ruta->muninombre]; 
                                    
        foreach ($rutaNodos as $item) {
            $municipioRutas[] = $item;
        }

        $tarifaTiquetes = DB::table('tarifatiquete')
                                ->select('tartiqid','tartiqdepaidorigen','tartiqmuniidorigen','tartiqdepaiddestino','tartiqmuniiddestino','tartiqvalor','tartiqfondoreposicion','tartiqvalorestampilla','tartiqvalorseguro',
                                DB::raw("CONCAT(FORMAT(tartiqvalor, 0)) as valorTiquete"),
                                DB::raw("CONCAT(FORMAT(tartiqvalorestampilla, 0)) as valorEstampilla"),
                                DB::raw("CONCAT(FORMAT(tartiqvalorseguro, 0)) as valorSeguro"))
                                ->where('rutaid', $request->codigo)->get();

        return response()->json(["municipioRutas" => $municipioRutas, "tarifaTiquetes" => $tarifaTiquetes]);
    }

    public function tiquete(Request $request)
	{
        $this->validate(request(),[
            'codigo'         => 'required|numeric',
            'departamento'   => 'required|numeric',
            'tarifaTiquetes' => 'required|array|min:1'
        ]);

        DB::beginTransaction();
        try {
           foreach($request->tarifaTiquetes as $data){
				$identificador       = $data['identificador'];
                $municipioOrigen     = $data['municipioOrigen'];
                $municipioDestino    = $data['municipioDestino'];
                $valorTiquete        = $data['valorTiquete'];
                $valorSeguro         = $data['valorSeguro'];
                $valorEstampilla     = $data['valorEstampilla'];
                $fondoReposicion     = $data['fondoReposicion'];
				$tarifaTiqueteEstado = $data['estado'];
				if($tarifaTiqueteEstado === 'I'){
					$tarifaTiquete                        = new TarifaTiquete();
					$tarifaTiquete->rutaid                = $request->codigo;
					$tarifaTiquete->tartiqdepaidorigen    = $request->departamentoOrigen;
                    $tarifaTiquete->tartiqmuniidorigen    = $municipioOrigen;
                    $tarifaTiquete->tartiqdepaiddestino   = $request->departamentoDestino;
                    $tarifaTiquete->tartiqmuniiddestino   = $municipioDestino;
                    $tarifaTiquete->tartiqvalor           = $valorTiquete;
                    $tarifaTiquete->tartiqvalorseguro     = $valorSeguro;
                    $tarifaTiquete->tartiqvalorestampilla = $valorEstampilla;
                    $tarifaTiquete->tartiqfondoreposicion = $fondoReposicion;
					$tarifaTiquete->save();
				}else if($tarifaTiqueteEstado === 'D'){
					$tarifaTiquete = TarifaTiquete::findOrFail($identificador);
					$tarifaTiquete->delete();
				}else{//Editar
                    $tarifaTiquete                        = TarifaTiquete::findOrFail($identificador);
                    $tarifaTiquete->tartiqdepaidorigen    = $request->departamentoOrigen;
                    $tarifaTiquete->tartiqmuniidorigen    = $municipioOrigen;
                    $tarifaTiquete->tartiqdepaiddestino   = $request->departamentoDestino;
                    $tarifaTiquete->tartiqmuniiddestino   = $municipioDestino;
                    $tarifaTiquete->tartiqvalor           = $valorTiquete;
                    $tarifaTiquete->tartiqvalorseguro     = $valorSeguro;
                    $tarifaTiquete->tartiqvalorestampilla = $valorEstampilla;
                    $tarifaTiquete->tartiqfondoreposicion = $fondoReposicion;
                    $tarifaTiquete->save();
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
            DB::beginTransaction();
			try {
                $ruta = Ruta::findOrFail($request->codigo);
                if ($ruta->has('rutaNodos')){ 
					foreach ($ruta->rutaNodos as $idRutaNodos){
						$ruta->rutaNodos()->delete($idRutaNodos);
					}
				}

                if ($ruta->has('tarifaTiquete')){ 
					foreach ($ruta->tarifaTiquete as $idTarifaTiquete){
						$ruta->tarifaTiquete()->delete($idTarifaTiquete);
					}
				}

				$ruta->delete();
                DB::commit();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
                DB::rollback();
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}