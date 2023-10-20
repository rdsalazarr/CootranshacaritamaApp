<?php

namespace App\Http\Controllers\Admin\Informacion;

use App\Models\Informacion\GeneralPdf;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class GeneralPdfController extends Controller
{
	public function index()
	{
        $data = DB::table('informaciongeneralpdf')->select('ingpdfid','ingpdfnombre','ingpdftitulo','ingpdfcontenido')->get();
        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
	{ 
        $id                    = $request->codigo;
        $informaciongeneralpdf = ($id != 000) ? GeneralPdf::findOrFail($id) : new GeneralPdf();

		$this->validate(request(),[
                'nombre'     => 'required|string|min:6|max:50|unique:informaciongeneralpdf,ingpdfid,'.$informaciongeneralpdf->ingpdfid.',ingpdfid',
                'titulo'     => 'required|string|min:4|max:100',
                'contenido'  => 'required|string'
			]);

		try {
            $informaciongeneralpdf->ingpdfnombre    = $request->nombre;
            $informaciongeneralpdf->ingpdftitulo    = $request->titulo;
            $informaciongeneralpdf->ingpdfcontenido = $request->contenido;
            $informaciongeneralpdf->save();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{	
		try {
			$informaciongeneralpdf = GeneralPdf::findOrFail($request->codigo);
			$informaciongeneralpdf->delete();
			return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
		}
	}
}