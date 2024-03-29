<?php

namespace App\Http\Controllers\Util;

use App\Models\ProducionDocumental\CodigoDocumentalProcesoAnexo;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Radicacion\DocumentoEntranteAnexo;
use App\Models\Conductor\ConductorCertificado;
use App\Models\Archivo\HistoricoDigitalizado;
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

    public function certificado(Request $request)
    {
        $this->validate(request(),['codigo' => 'required','rutaFile' => 'required','documento' => 'required']);

        DB::beginTransaction();
        try {
	    	$rutaFile  = Crypt::decrypt($request->rutaFile);
            $documento = $request->documento;
            $idFile    = $request->codigo;
            $carpeta   = '/archivos/persona/';
            $rutaFull  = public_path().$carpeta.$documento.'/'.$rutaFile;

            $conductorcertificado = ConductorCertificado::findOrFail($idFile);
			$conductorcertificado->delete();

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