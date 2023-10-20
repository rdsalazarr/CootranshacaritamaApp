<?php

namespace App\Http\Controllers\Admin\Tipos;

use App\Http\Controllers\Controller;
use App\Models\Tipos\TipoSaludo;
use Illuminate\Http\Request;
use Exception, DB;

class SaludoController extends Controller
{
    public function index()
    {
        $data = DB::table('tiposaludo')->select('tipsalid','tipsalnombre','tipsalactivo',
                                    DB::raw("if(tipsalactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('tipsalnombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id         = $request->codigo;
        $tiposaludo = ($id != 000) ? TipoSaludo::findOrFail($id) : new TipoSaludo();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:100', 
	            'estado' => 'required'
	        ]);

        try {
            $tiposaludo->tipsalnombre = $request->nombre;
            $tiposaludo->tipsalactivo = $request->estado;
            $tiposaludo->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$oficio   = DB::table('coddocumprocesooficio')->select('tipsalid')->where('tipdesid', $request->codigo)->first();
		$circular = DB::table('coddocumprocesocircular')->select('tipsalid')->where('tipsalid', $request->codigo)->first();
		if($oficio || $circular){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un oficio o a una circular del sistema']);
		}else{
			try {
				$tiposaludo = TipoSaludo::findOrFail($request->codigo);
				$tiposaludo->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}