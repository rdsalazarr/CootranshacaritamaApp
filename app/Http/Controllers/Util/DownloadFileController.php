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

    public function certificado($documento, $ruta){
        try {
	    	$ruta    = Crypt::decrypt($ruta);
            $carpeta = '/archivos/persona/'.$documento.'/';
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

    public function showAdjunto(Request $request)
	{
        $this->validate(request(),['ruta' => 'required']);
        try {
	    	$ruta    = Crypt::decrypt($request->rutaEnfuscada);
            $carpeta = $request->ruta;
            $file = public_path().'/'.$carpeta.'/'.$ruta;
            if (file_exists($file)) {
                $stream = @fopen($file, 'r');
                if (!$stream) {
                    return response()->json(['successError' => true, 'message'=> 'Error al abir el archivo => '.$ruta]); 
                }
                $img = @stream_get_contents($stream);
                $archivo = base64_encode($img);
                @fclose($stream);
                return response()->json(["data" => $archivo]);
            } else {
                return response()->json(['successError' => true, 'message'=> 'Error el archivo no fue encontrado']);
            }
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		} 
    }

    public function contrato($placa, $ruta){
        try {
	    	$placa   = Crypt::decrypt($placa);
            $ruta    = Crypt::decrypt($ruta);
            $carpeta = '/archivos/vehiculo/'.$placa.'/';
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