<?php

namespace App\Http\Controllers\Admin\Series;
use App\Http\Controllers\Controller;
use App\Models\SerieDocumental;
use Illuminate\Http\Request;
use Exception, DB;

class SerieDocumentalController extends Controller
{
    public function index()
    {
        $data = DB::table('seriedocumental')->select('serdocid','serdoccodigo','serdocnombre','serdoctiempoarchivogestion','serdoctiempoarchivocentral',
                                    'serdoctiempoarchivohistorico','serdocpermiteeliminar','serdocactiva',
									DB::raw("if(serdocpermiteeliminar = 1 ,'Sí', 'No') as permiteeliminar"),
                                    DB::raw("if(serdocactiva = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('serdoccodigo')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{
        $id      = $request->id;
        $seriedocumental = ($id != 000) ? SerieDocumental::findOrFail($id) : new SerieDocumental();

	    $this->validate(request(),[
	   			'codigo'                 => 'required|string|min:1|max:3|unique:seriedocumental,serdoccodigo,'.$seriedocumental->serdocid.',serdocid',
	   	        'nombre'                 => 'required|string|min:4|max:80',
	            'tiempoArchivoGestion'   => 'required|numeric|min:1|max:9999',
	            'tiempoArchivoCentral'   => 'required|numeric|min:1|max:9999',
	            'tiempoArchivoHistorico' => 'required|numeric|min:1|max:9999',
				'permiteEliminar'        => 'required',
	            'estado'                 => 'required'
	        ]);

        try {
            $seriedocumental->serdoccodigo                 = $request->codigo;
            $seriedocumental->serdocnombre                 = $request->nombre;
            $seriedocumental->serdoctiempoarchivogestion   = $request->tiempoArchivoGestion;
            $seriedocumental->serdoctiempoarchivocentral   = $request->tiempoArchivoCentral;
            $seriedocumental->serdoctiempoarchivohistorico = $request->tiempoArchivoHistorico;
            $seriedocumental->serdocpermiteeliminar        = $request->permiteEliminar;
            $seriedocumental->serdocactiva                 = $request->estado;
            $seriedocumental->save();
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
				$seriedocumental = SerieDocumental::findOrFail($request->codigo);
				$seriedocumental->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}