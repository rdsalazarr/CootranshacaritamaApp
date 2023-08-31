<?php

namespace App\Http\Controllers\Admin\Menu;

use App\Http\Controllers\Controller;
use App\Models\RolFuncionalidad;
use Illuminate\Http\Request;
use App\Models\Rol;
use Carbon\Carbon;
use DB;

class RolController extends Controller
{  
	public function index()
	{   
        $data = DB::table('rol')
                    ->select('rolid', 'rolnombre', 'rolactivo', 
                        DB::raw("if(rolactivo = 1 ,'Sí', 'No') as estado"))
                    ->orderBy('rolnombre')->get();
		return response()->json(["data" => $data]);
	}

    public function funcionalidades(Request $request)
	{
		$data = DB::table('funcionalidad as f')
						->select('f.funcid',DB::raw("CONCAT(f.funcnombre,'-', m.modunombre) as titulo"))
						->join('modulo as m', 'm.moduid', '=', 'f.moduid')
	    				->where('f.funcactiva', 1)
						->orderBy('m.modunombre')
						->orderBy('f.funcnombre')->get();

		$marcados = DB::table('rolfuncionalidad')->select('rolfunfuncid as funcid')
						->where('rolfunrolid', $request->codigo)->get();

        return response()->json(["data" => $data, "marcados" => $marcados ]);
	}
	
	public function salve(Request $request)
	{
        $this->validate(request(),[
				'codigo' => 'required',
				'nombre'          => 'required|string|min:4|max:80',
				'estado'          => 'required',
				'funcionalidades' => 'required|array|min:1',
            ]);

        DB::beginTransaction();        
        try {
			$fechaHoraActual = Carbon::now();
			if($request->tipo === 'I'){
				$idRol = DB::table('rol')
						->insertGetId(['rolnombre' => $request->nombre,
								'rolactivo'  => $request->estado,
								'created_at' => $fechaHoraActual,
								'updated_at' => $fechaHoraActual]);
			}else{
				$idRol          = $request->codigo;
				$rol            = Rol::findOrFail($idRol);
				$rol->rolnombre = $request->nombre; 
	            $rol->rolactivo = $request->estado;
	            $rol->save(); 
			}			

			//Elimino las funcionalides asignada
			if($request->tipo === 'U'){
				$rolfuncionalidad = DB::table('rolfuncionalidad')->select('rolfunid')
						->where('rolfunrolid', $request->codigo)->get();
				foreach ($rolfuncionalidad as $funcionalidad)
            	{
					$rolfuncionalidad = RolFuncionalidad::findOrFail($funcionalidad->rolfunid);
					$rolfuncionalidad->delete();
				}
			}

            //Almaceno las funcionalidades
            foreach ($request->funcionalidades as $funcionalidad)
            {
                $rolfuncionalidad= new RolFuncionalidad();
                $rolfuncionalidad->rolfunrolid = $idRol;
                $rolfuncionalidad->rolfunfuncid = $funcionalidad['funcid'];
                $rolfuncionalidad->save();
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
        DB::beginTransaction();
        try {
			$rol = Rol::findOrFail($request->codigo);
			if ($rol->has('funcionalidades')){ 
				foreach ($rol->funcionalidades as $idFuncionalidad){
					$rol->funcionalidades()->delete($idFuncionalidad);
				} 
			}
			$rol->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
        }			
	} 
}