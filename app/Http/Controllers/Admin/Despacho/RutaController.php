<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Http\Controllers\Controller;
use App\Models\Despacho\Ruta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception, DB;

class RutaController extends Controller
{
    public function index(){
        $data = DB::table('ruta as r')
                    ->select('r.rutaid','do.depanombre as nombreDeptoOrigen', 'mo.muninombre as nombreMunicipioOrigen',   
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

        $departamentos = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get();
        $municipios    = DB::table('municipio')->select('muniid','munidepaid','muninombre')->orderBy('muninombre')->get();
        $ruta          = [];
        if($request->tipo === 'U'){
            $ruta = DB::table('ruta')->select('rutaid','depaidorigen','muniidorigen','depaiddestino','muniiddestino','rutaactiva')
                        ->where('rutaid', $request->codigo)->first();
        }

        return response()->json(["departamentos" => $departamentos, "municipios" => $municipios, "ruta" => $ruta]);
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
            'estado'               => 'required'
        ]);

        DB::beginTransaction();
        try {

            $ruta->depaidorigen  = $request->departamentoOrigen;
            $ruta->muniidorigen  = $request->municipioOrigen;
            $ruta->depaiddestino = $request->departamentoDestino;
            $ruta->muniiddestino = $request->municipioDestino;
            $ruta->rutaactiva    = $request->estado;
            $ruta->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito'.$mensajeNotificar, 'planillaId' => $coseesid]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function destroy(Request $request)
	{
        $dependenciapersona = DB::table('dependenciapersona')->select('rutaid')->where('rutaid', $request->codigo)->first();
		if($dependenciapersona){
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