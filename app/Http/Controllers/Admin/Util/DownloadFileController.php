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
    
    public function download($folder, $ruta, $id){
        $array = array(
            '' => 0, 
            '/archivos/produccionDocumental/adjuntos/' => 1,
            '/archivos/beneficiario/' => 2, 
            '/archivos/inmueble/' => 3,
            '/archivos/' => 4 //Descarga los contratos
        );
    
        try {
	    	$ruta    = Crypt::decrypt($ruta); 	
            $carpeta = array_search($id, $array).$folder.'/'; 
          
            if($carpeta) {
                $file = public_path().$carpeta.$ruta;
                return response()->download($file, $ruta);
            }else{
                return redirect('/error/url');
            }	
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}
    }
}