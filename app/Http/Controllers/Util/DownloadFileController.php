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
    
    public function download($sigla, $anyo, $ruta, $id){
        $array = array(
            '' => 0, 
            '/archivos/produccionDocumental/adjuntos/' => 1,
            '/archivos/beneficiario/' => 2, 
            '/archivos/inmueble/' => 3,
            '/archivos/' => 4 //Descarga los contratos
        );
    
        try {
	    	$ruta    = Crypt::decrypt($ruta);
            $carpeta = array_search($id, $array).$sigla.'/'.$anyo.'/';
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