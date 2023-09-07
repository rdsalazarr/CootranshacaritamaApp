<?php

namespace App\Http\Controllers\Admin\Series;
use App\Http\Controllers\Controller;
use App\Models\SubSerieDocumental;
use Illuminate\Http\Request;
use DB;

class SubSerieDocumentalController extends Controller
{
    public function index()
    {  
        $data = DB::table('subseriedocumental as ssd')
       					->select('ssd.susedoid','ssd.susedocodigo','ssd.susedonombre','ssd.susedopermiteeliminar','ssd.susedoactiva','ssd.serdocid',
	    						'sd.serdoccodigo as seriecodigo','sd.serdocnombre as serienombre','td.tipdocnombre as tipodocumental','ssd.tipdocid',
								DB::raw("if(ssd.susedopermiteeliminar = 1 ,'Sí', 'No') as permiteEliminar"),
                                DB::raw("if(ssd.susedoactiva = 1 ,'Sí', 'No') as estado"))
	       				->join('seriedocumental as sd', 'sd.serdocid', '=', 'ssd.serdocid')
		    			->join('tipodocumental as td', 'td.tipdocid', '=', 'ssd.tipdocid')
		    			->orderBy('sd.serdoccodigo')
		    			->orderBy('ssd.susedocodigo')->get();
 
        return response()->json(["data" => $data]);
    }

    public function datos()
	{
		$tipoDocumentales  = DB::table('tipodocumental')->select('tipdocid','tipdocnombre')->where('tipdocactivo',1)->orderBy('tipdocnombre')->get();
		$seriedocumentales = DB::table('seriedocumental')->select('serdocid','serdocnombre')->where('serdocactiva',1)->orderBy('serdocnombre')->get();
        return response()->json(["seriedocumentales" => $seriedocumentales, "tipoDocumentales" => $tipoDocumentales ]);
	}

    public function salve(Request $request)
	{
        $id      = $request->id;
        $subseriedocumental = ($id != 000) ? SubSerieDocumental::findOrFail($id) : new SubSerieDocumental();
	
	    $this->validate(request(),[
				'codigo'          => 'required|numeric|unique:subseriedocumental,susedocodigo,'.$subseriedocumental->susedoid.',susedoid,serdocid,'.$request->serie,
	    	    'nombre'          => 'required|string|min:4|max:80',
	    	    'serie'           => 'required',
	    	    'tipoDocumento'   => 'required',
                'permiteEliminar' => 'required',
	            'estado'          => 'required'
	        ]);

        try {
            $subseriedocumental->serdocid              = $request->serie;
            $subseriedocumental->tipdocid              = $request->tipoDocumento;
            $subseriedocumental->susedocodigo          = $request->codigo;
            $subseriedocumental->susedonombre          = $request->nombre;
            $subseriedocumental->susedopermiteeliminar = $request->permiteEliminar;
            $subseriedocumental->susedoactiva          = $request->estado;
            $subseriedocumental->save();
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
				$subseriedocumental = SubSerieDocumental::findOrFail($request->codigo);
				$subseriedocumental->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}