<?php

namespace App\Http\Controllers\Util;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\CodigoDocumentalProcesoAnexo;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  DB;

class EliminarAchivosController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}

    public function index(Request $request)
    {
        $this->validate(request(),['codigo' => 'required','rutaFile' => 'required','sigla' => 'required','anyo' => 'required']);

        $array = array(
            '' => 0, 
            '/archivos/produccionDocumental/adjuntos/' => 1
        );

        DB::beginTransaction();
        try {
	    	$rutaFile = Crypt::decrypt($request->rutaFile);
            $sigla    = $request->sigla;
            $anyo     = $request->anyo;
            $idFolder = $request->idFolder;
            $idFile   = $request->codigo;
            $carpeta  = array_search($idFolder, $array);
            $rutaFull = public_path().$carpeta.$sigla.'/'.$anyo.'/'.$rutaFile;

            $coddocumprocesoanexo = CodigoDocumentalProcesoAnexo::findOrFail($idFile);
			$coddocumprocesoanexo->delete();

            unlink($rutaFull);//Elimina el archivo de la carpeta
        
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
		} catch (DecryptException $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }    
}