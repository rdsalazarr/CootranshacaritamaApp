<?php

namespace App\Http\Controllers\Admin\Tipos;

use App\Http\Controllers\Controller;
use App\Models\Tipos\TipoSancion;
use Illuminate\Http\Request;
use Exception, DB;

class SancionController extends Controller
{
    public function index()
    {
        $data = DB::table('tiposancion')->select('tirsanid','tirsannombre','tirsanactivo',
                                    DB::raw("if(tirsanactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('tirsannombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id         = $request->codigo;
        $tiposancion = ($id != 000) ? TipoSancion::findOrFail($id) : new TipoSancion();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:50', 
	            'estado' => 'required'
	        ]);

        try {
            $tiposancion->tirsannombre = $request->nombre;
            $tiposancion->tirsanactivo = $request->estado;
            $tiposancion->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$circular = DB::table('coddocumprocesocircular')->select('tirsanid')->where('tirsanid', $request->codigo)->first();
		if($circular){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un oficio o a una circular del sistema']);
		}else{
			try {
				$tiposancion = TipoSancion::findOrFail($request->codigo);
				$tiposancion->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}