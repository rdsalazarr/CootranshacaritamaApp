<?php

namespace App\Http\Controllers\Admin\Tipos;
use App\Http\Controllers\Controller;
use App\Models\TipoDespedida;
use Illuminate\Http\Request;
use DB;

class DespedidaController extends Controller
{
    public function index()
    {  
        $data = DB::table('tipodespedida')->select('tipdesid','tipdesnombre','tipdesactivo',
                                    DB::raw("if(tipdesactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('tipdesnombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id            = $request->codigo;
        $tipodespedida = ($id != 000) ? TipoDespedida::findOrFail($id) : new TipoDespedida();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:100', 
	            'estado' => 'required'
	        ]);

        try {
            $tipodespedida->tipdesnombre = $request->nombre;
            $tipodespedida->tipdesactivo = $request->estado;
            $tipodespedida->save();
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
				$tipodespedida = TipoDespedida::findOrFail($request->codigo);
				$tipodespedida->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}
