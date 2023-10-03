<?php

namespace App\Http\Controllers\Admin\Tipos;
use App\Http\Controllers\Controller;
use App\Models\TipoEstanteArchivador;
use Illuminate\Http\Request;
use Exception, DB;

class EstanteArchivadorController extends Controller
{
    public function index()
    {
        $data = DB::table('tipoestantearchivador')->select('tiesarid','tiesarnombre','tiesaractivo',
                                    DB::raw("if(tiesaractivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('tiesarnombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                    = $request->codigo;
        $tipoestantearchivador = ($id != 000) ? TipoEstanteArchivador::findOrFail($id) : new TipoEstanteArchivador();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:50', 
	            'estado' => 'required'
	        ]);

        try {
            $tipoestantearchivador->tiesarnombre = $request->nombre;
            $tipoestantearchivador->tiesaractivo = $request->estado;
            $tipoestantearchivador->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$archivohistorico = DB::table('archivohistorico')->select('tiesarid')->where('tiesarid', $request->codigo)->first();
		if($archivohistorico){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un archivo histórico del sistema']);
		}else{
			try {
				$tipoestantearchivador = TipoEstanteArchivador::findOrFail($request->codigo);
				$tipoestantearchivador->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}