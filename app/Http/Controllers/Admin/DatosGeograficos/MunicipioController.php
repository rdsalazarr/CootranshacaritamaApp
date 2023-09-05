<?php

namespace App\Http\Controllers\Admin\DatosGeograficos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Municipio;
use DB;

class MunicipioController extends Controller
{  
	public function index()
	{   
		$data = DB::table('municipio as m')
                            ->select('m.muniid','m.munidepaid','m.municodigo','m.muninombre','m.munihacepresencia','d.depanombre',
								DB::raw("if(munihacepresencia = 1 ,'Sí', 'No') as hacePresencia"))
                            ->join('departamento as d', 'd.depaid', '=', 'm.munidepaid')
                            ->orderBy('d.depanombre')->orderBy('m.muninombre')->get();

		return response()->json(["data" => $data]);
	}

    public function deptos()
	{
		$departamentos = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get(); 
		return response()->json(["data" => $departamentos]);
	}
	
	public function salve(Request $request)
	{
	   $this->validate(request(),[
            'id'      => 'required|numeric',
            'depto'   => 'required|numeric',
            'codigo'  => 'required|numeric',
            'nombre'  => 'required|string|min:4|max:80',
            'hacePresencia' => 'required'
        ]);  
		
		try {
			$municipio = Municipio::findOrFail($request->id);
			$municipio->munidepaid = $request->depto;
			$municipio->municodigo = $request->codigo;
            $municipio->muninombre = $request->nombre;
			$municipio->munihacepresencia  = $request->hacePresencia;
			$municipio->save();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}	
}