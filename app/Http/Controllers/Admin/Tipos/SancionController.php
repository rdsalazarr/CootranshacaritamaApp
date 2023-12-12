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
        $data = DB::table('tiposancion')->select('tipsanid','tipsannombre','tipsanactivo',
                                    DB::raw("if(tipsanactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('tipsannombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id          = $request->codigo;
        $tiposancion = ($id != 000) ? TipoSancion::findOrFail($id) : new TipoSancion();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:50', 
	            'estado' => 'required'
	        ]);

        try {
            $tiposancion->tipsannombre = $request->nombre;
            $tiposancion->tipsanactivo = $request->estado;
            $tiposancion->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$asociadosancion = DB::table('asociadosancion')->select('tipsanid')->where('tipsanid', $request->codigo)->first();
		if($asociadosancion){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una sanción del asociado']);
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