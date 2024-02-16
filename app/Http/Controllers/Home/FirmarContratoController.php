<?php

namespace App\Http\Controllers\Home;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Util\generarPlanilla;
use Illuminate\Http\Request;
use App\Util\encrypt;
use App\Models\User;
use DB, URL;

class FirmarContratoController extends Controller
{
	public function index($id, $id2)
	{
		//Verifico las variables
		try {
	    	$contratoId = Crypt::decrypt($id); 
			$idFirma    = Crypt::decrypt($id2); 
		} catch (DecryptException $e) {
		   return redirect('/error/url');
		}

        dd($contratoId, $idFirma);



		$user = DB::table('users')->select('id','nombre', 'apellidos','usuario','activo')
								->where('email',$correo)->first();
		if(!$user->activo){
			$usuario = User::findOrFail($user->id);
			$usuario->activo = true;
	        $usuario->save();
	    }

	    $usuario = $user->nombre.' '.$user->apellidos;
	    $mensaje = 'Tu cuenta ha sido activada exitosamente.';

        return view('home.activar',['usuario' => $usuario,'mensaje' => $mensaje, 'title' => 'Activación de la cuenta']);
	}

}