<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\Menu\Funcionalidad;
use Illuminate\Http\Request;
use Exception, DB;

class FuncionalidadController extends Controller
{
    public function index()
	{
		$data = DB::table('funcionalidad as f')
							->select('f.funcnombre', 'f.funcid', 'f.functitulo','f.funcicono',
								 'f.funcruta', 'f.funcorden', 'f.funcactiva',
								 DB::raw("if(f.funcactiva = 1 ,'Sí', 'No') as estado"),	
								 'modunombre','m.moduid')
							->join('modulo as m', 'm.moduid', '=', 'f.moduid')
							->orderBy('m.moduorden')->orderBy('f.funcorden')->get();

		return response()->json(["data" => $data]);
	}

	public function modulos()
	{
		$modulos = DB::table('modulo')->select('moduid','modunombre')
					->where('moduactivo', 1)->orderBy('modunombre')->get(); 

		return response()->json(["data" => $modulos]);
	}

	public function salve(Request $request)
	{
	   $this->validate(request(),[
			'codigo' => 'required',
			'modulo' => 'required|numeric',
            'nombre' => 'required|string|min:3|max:80',
			'titulo' => 'required|string|min:3|max:80',
            'ruta'   => 'required|string|min:4|max:60', 
			'icono'  => 'required|string|min:2|max:30', 
            'orden'  => 'required|numeric',
            'estado' => 'required'
        ]);

		try {
			$id            = $request->codigo;
			$funcionalidad = ($id != 000) ? Funcionalidad::findOrFail($id) : new Funcionalidad();
			$funcionalidad->moduid      = $request->modulo;
			$funcionalidad->funcnombre  = $request->nombre;
			$funcionalidad->functitulo  = $request->titulo;
			$funcionalidad->funcruta    = $request->ruta;
			$funcionalidad->funcicono   = $request->icono;
			$funcionalidad->funcorden   = $request->orden;
			$funcionalidad->funcactiva  = $request->estado;
			$funcionalidad->save();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function destroy(Request $request)
	{
		$rolfuncionalidad = DB::table('rolfuncionalidad')
					->select('rolfunfuncid')
					->where('rolfunfuncid', $request->codigo)->first();

		if($rolfuncionalidad){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un rol del sistema']);
		}else{
			try {
				$funcionalidad = Funcionalidad::findOrFail($request->codigo);
				$funcionalidad->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}