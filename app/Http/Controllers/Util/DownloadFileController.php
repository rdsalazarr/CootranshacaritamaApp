<?php

namespace App\Http\Controllers\Util;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadFileController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}

    public function download($sigla, $anyo, $ruta){
        try {
	    	$ruta    = Crypt::decrypt($ruta);
            $carpeta = '/archivos/produccionDocumental/adjuntos/'.$sigla.'/'.$anyo.'/';
            $file = public_path().$carpeta.$ruta;
            if (file_exists($file)) {
                return response()->download($file, $ruta);
            } else {
                return redirect('/archivoNoEncontrado'.$carpeta.$ruta);
            }
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}
    }

    public function radicadoEntrante($anyo, $ruta){
        try {
	    	$ruta    = Crypt::decrypt($ruta);
            $carpeta = '/archivos/radicacion/documentoEntrante/'.$anyo.'/';
            $file = public_path().$carpeta.$ruta;
            if (file_exists($file)) {
                return response()->download($file, $ruta);
            } else {
                return redirect('/archivoNoEncontrado'.$carpeta.$ruta);
            }
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}
    }

    public function digitalizados($anyo, $ruta){
        try {
	    	$ruta    = Crypt::decrypt($ruta);
            $carpeta = '/archivos/digitalizados/'.$anyo.'/';
            $file = public_path().$carpeta.$ruta;
            if (file_exists($file)) {
                return response()->download($file, $ruta);
            } else {
                return redirect('/archivoNoEncontrado'.$carpeta.$ruta);
            }
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}
    }    
}