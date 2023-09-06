<?php

namespace App\Http\Controllers\Admin\Tipos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TipoSaludo;
use DB;

class TipoSaludoController extends Controller
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
            $tiposaludo->tipdesnombre = $request->nombre;           
            $tiposaludo->tipdesactivo = $request->estado;
            $tiposaludo->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$subseriedocumental = DB::table('subseriedocumental')
					->select('serdocid')
					->where('serdocid', $request->codigo)->first();

		if($subseriedocumental){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una serie documental del sistema']);
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
