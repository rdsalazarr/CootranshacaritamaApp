<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dependencia;
use DB;
    
class DependenciaController extends Controller
{
    public function index()
    {  
        $data = DB::table('dependencia as d')->select('d.depeid','d.depejefeid','d.depecodigo','d.depesigla','d.depenombre',
                                    'd.depecorreo','d.depeactiva',
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreJefe"),
                                    DB::raw("if(d.depeactiva = 1 ,'Sí', 'No') as estado"))
                                    ->join('persona as p', 'p.persid', '=', 'd.depejefeid')
                                    ->orderBy('d.depenombre')->get();
        return response()->json(["data" => $data]);
    }

    public function datos()
	{ 
		$jefes = DB::table('persona')
                        ->select('persid',DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre),' ',
                        persprimerapellido,' ',if(perssegundoapellido is null ,' ', perssegundoapellido)) as nombreJefe"))
                        ->where('persactiva',1)
                        ->orderBy('persprimernombre')->orderBy('perssegundonombre')
                        ->orderBy('persprimerapellido')->orderBy('perssegundoapellido')->get();

        
        return response()->json(["jefes" => $jefes ]);
	}

    public function salve(Request $request)
	{
        $id      = $request->id;
        $dependencia = ($id != 000) ? Dependencia::findOrFail($id) : new Dependencia();

	    $this->validate(request(),[
	   			'codigo' => 'required|string|min:1|max:10|unique:dependencia,depecodigo,'.$dependencia->depeid.',depeid',
                'sigla'  => 'required|string|min:1|max:3|unique:dependencia,depesigla,'.$dependencia->depeid.',depeid',
	   	        'nombre' => 'required|string|min:4|max:80',
                'correo' => 'required|string|min:4|max:80',
                'jefe'   => 'required',
	            'estado' => 'required'
	        ]);

        try {
            $dependencia->depejefeid = $request->jefe;
            $dependencia->depecodigo = $request->codigo;
            $dependencia->depesigla  = mb_strtoupper($request->sigla,'UTF-8');
            $dependencia->depenombre = mb_strtoupper($request->nombre,'UTF-8');
            $dependencia->depecorreo = $request->correo;
            $dependencia->depeactiva = $request->estado;
            $dependencia->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$subdependencia = DB::table('dependencia')
					->select('depeid')
					->where('depeid', $request->codigo)->first();

		if($subdependencia){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una serie documental del sistema']);
		}else{
			try {
				$dependencia = Dependencia::findOrFail($request->codigo);
				$dependencia->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}