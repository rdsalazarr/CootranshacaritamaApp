<?php

namespace App\Http\Controllers\Admin\Tipos;
use App\Http\Controllers\Controller;
use App\Models\TipoPersonaDocumental;
use Illuminate\Http\Request;
use Exception, DB;

class PersonaDocumentalController extends Controller
{
    public function index()
    {
        $data = DB::table('tipopersonadocumental')->select('tipedoid','tipedonombre','tipedoactivo',
                                    DB::raw("if(tipedoactivo = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('tipedonombre')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id                    = $request->codigo;
        $tipopersonadocumental = ($id != 000) ? TipoPersonaDocumental::findOrFail($id) : new TipoPersonaDocumental();

	    $this->validate(request(),[
	   	        'nombre' => 'required|string|min:4|max:150',
	            'estado' => 'required'
	        ]);

        try {
            $tipopersonadocumental->tipedonombre = $request->nombre;
            $tipopersonadocumental->tipedoactivo = $request->estado;
            $tipopersonadocumental->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$certificado = DB::table('coddocumprocesocertificado')->select('tipedoid')->where('tipedoid', $request->codigo)->first();
        $constancia  = DB::table('coddocumprocesoconstancia')->select('tipedoid')->where('tipedoid', $request->codigo)->first();
		if($certificado || $constancia ){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una constancia o aun certificado documental del sistema']);
		}else{
			try {
				$tipopersonadocumental = TipoPersonaDocumental::findOrFail($request->codigo);
				$tipopersonadocumental->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}