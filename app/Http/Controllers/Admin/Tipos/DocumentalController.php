<?php

namespace App\Http\Controllers\Admin\Tipos;
use App\Http\Controllers\Controller;
use App\Models\TipoDocumental;
use Illuminate\Http\Request;
use Exception, DB;

class DocumentalController extends Controller
{
    public function index()
    {
        $data = DB::table('tipodocumental')->select('tipdocid','tipdoccodigo','tipdocnombre','tipdocactivo',
                                    DB::raw("if(tipdocactivo = 1 ,'Sí', 'No') as estado"))
                                    ->where('tipdocproducedocumento', false)
                                    ->orderBy('tipdocnombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id             = $request->id;
        $tipodocumental = ($id != 000) ? TipoDocumental::findOrFail($id) : new TipoDocumental();

	    $this->validate(request(),[
                'codigo' => 'required|string|min:1|max:3|unique:tipodocumental,tipdoccodigo,'.$tipodocumental->tipdocid.',tipdocid',
	   	        'nombre' => 'required|string|min:4|max:50',
	            'estado' => 'required'
	        ]);

        try {
            $tipodocumental->tipdoccodigo = mb_strtoupper($request->codigo,'UTF-8');
            $tipodocumental->tipdocnombre = $request->nombre;
            $tipodocumental->tipdocactivo = $request->estado;
            $tipodocumental->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$archivohistorico = DB::table('archivohistorico')->select('tipdocid')->where('tipdocid', $request->codigo)->first();
		if($archivohistorico){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un archivo histórico del sistema']);
		}else{
			try {
				$tipodocumental = TipoDocumental::findOrFail($request->codigo);
				$tipodocumental->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}