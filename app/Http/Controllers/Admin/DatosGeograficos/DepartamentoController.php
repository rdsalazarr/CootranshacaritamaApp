<?php

namespace App\Http\Controllers\Admin\DatosGeograficos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Departamento;
use Exception, DB;

class DepartamentoController extends Controller
{
	public function index()
	{
		$data = DB::table('departamento')
                            ->select('depaid','depacodigo','depanombre','depahacepresencia',
								DB::raw("if(depahacepresencia = 1 ,'Sí', 'No') as hacePresencia"))
                            ->orderBy('depanombre')->get();

		return response()->json(["data" => $data]);
	}

	public function salve(Request $request)
	{
	   $this->validate(request(),[
            'id'      => 'required|numeric',
            'codigo'  => 'required|numeric',
            'nombre'  => 'required|string|min:4|max:80',
            'hacePresencia' => 'required'
        ]);

		try {
			$departamento = Departamento::findOrFail($request->id);
			$departamento->depacodigo = $request->codigo;
			$departamento->depanombre = $request->nombre;
			$departamento->depahacepresencia  = $request->hacePresencia;
			$departamento->save();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}