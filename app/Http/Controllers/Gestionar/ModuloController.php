<?php

namespace App\Http\Controllers\Gestionar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Modulo;
use DB;

class ModuloController extends Controller
{
    public function index()
	{
		$data = DB::table('modulo as m')
                            ->select('m.moduid','m.modunombre','m.moduicono','m.moduorden','m.moduactivo',
								DB::raw("if(m.moduactivo = 1 ,'Sí', 'No') as estado"),
                                DB::raw('(SELECT COUNT(funcid) AS funcid FROM funcionalidad WHERE moduid = m.moduid) AS totalMenu'))                           
                            ->orderBy('m.moduorden')->get();

		return response()->json(["data" => $data]);
	}

	public function salve(Request $request)
	{
	   $this->validate(request(),[
            'nombre' => 'required|string|min:3|max:80',
            'icono'  => 'required|string|min:4|max:30', 
            'orden'  => 'required|numeric',
            'estado' => 'required'
        ]);
		
		try {
			$id = $request->codigo;	
			$modulo = ($id != 999) ? Modulo::findOrFail($id) : new Modulo();
			$modulo->modunombre = $request->nombre;
			$modulo->moduicono  = $request->icono;
			$modulo->moduorden  = $request->orden;
			$modulo->moduactivo = $request->estado;
			$modulo->save();
			return response()->json(['success' => true, 'data' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'data'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function destroy(Request $request)
	{		
		$modulo = DB::table('funcionalidad')->select('moduid')
				->where('moduid', $request->icodigod)->first();

		if($modulo){
			return response()->json(['success' => false, 'data'=> 'Este registro no se puede eliminar, porque está relacionado con una funcionalidad del sistema']);
		}else{
			try {
				$funcionalidad = Funcionalidad::findOrFail($request->codigo);
				$funcionalidad->delete();
				return response()->json(['success' => true, 'data' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'data'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	} 
}