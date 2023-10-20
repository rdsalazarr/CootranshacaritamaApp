<?php

namespace App\Http\Controllers\Admin\Tipos;

use App\Http\Controllers\Controller;
use App\Models\Tipos\CargoLaboral;
use Illuminate\Http\Request;
use Exception, DB;

class CargoLaboralController extends Controller
{
    public function index()
    {
        $data = DB::table('cargolaboral')->select('carlabid','carlabnombre','carlabactivo',
                                    DB::raw("if(carlabactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('carlabnombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id            = $request->codigo;
        $cargolaboral = ($id != 000) ? CargoLaboral::findOrFail($id) : new CargoLaboral();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:100', 
	            'estado' => 'required'
	        ]);

        try {
            $cargolaboral->carlabnombre = $request->nombre;
            $cargolaboral->carlabactivo = $request->estado;
            $cargolaboral->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$persona = DB::table('persona')->select('carlabid')->where('carlabid', $request->codigo)->first();
		if($persona){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una persona del sistema']);
		}else{
			try {
				$cargolaboral = CargoLaboral::findOrFail($request->codigo);
				$cargolaboral->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}