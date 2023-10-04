<?php

namespace App\Http\Controllers\Util;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\RadicacionDocumentoEntranteAnexo;
use App\Models\ArchivoHistoricoDigitalizado;
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

        DB::beginTransaction();
        try {
	    	$rutaFile = Crypt::decrypt($request->rutaFile);
            $sigla    = $request->sigla;
            $anyo     = $request->anyo;
            $idFile   = $request->codigo;
            $carpeta  = '/archivos/produccionDocumental/adjuntos/';
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

    public function radicadoEntrante(Request $request)
    {
        $this->validate(request(),['codigo' => 'required','rutaFile' => 'required','anyo' => 'required']);

        DB::beginTransaction();
        try {
	    	$rutaFile = Crypt::decrypt($request->rutaFile);
            $anyo     = $request->anyo;
            $idFile   = $request->codigo;
            $carpeta  = '/archivos/radicacion/documentoEntrante/'.$anyo.'/';
            $rutaFull = public_path().$carpeta.$rutaFile;

            $eliminarAnexosRadicado = RadicacionDocumentoEntranteAnexo::findOrFail($idFile);
			$eliminarAnexosRadicado->delete();

            unlink($rutaFull);//Elimina el archivo de la carpeta
        
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
		} catch (DecryptException $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function digitalizados(Request $request)
    {
        $this->validate(request(),['codigo' => 'required','rutaFile' => 'required','anyo' => 'required']);

        DB::beginTransaction();
        try {
	    	$rutaFile = Crypt::decrypt($request->rutaFile);
            $anyo     = $request->anyo;
            $idFile   = $request->codigo;
            $carpeta  = '/archivos/digitalizados/'.$anyo.'/';
            $rutaFull = public_path().$carpeta.$rutaFile;

            $eliminarArchivoDigitalizados = ArchivoHistoricoDigitalizado::findOrFail($idFile);
			$eliminarArchivoDigitalizados->delete();

            unlink($rutaFull);//Elimina el archivo de la carpeta
        
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
		} catch (DecryptException $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    } 
}