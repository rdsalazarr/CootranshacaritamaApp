<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistorialContrasena;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use App\Util\generales;
use App\Models\User;

class PerfilUsuarioController extends Controller
{
    public function index()
	{ 
		$dataUsuario = ['nombreCompleto' => Auth::user()->usuanombre.' '.Auth::user()->usuaapellidos,
						'usuanick'       => Auth::user()->usuanick
						];
		return response()->json(["dataUsuario" => $dataUsuario]);
	}    
    
    public function perfil()
	{
		$usuario = DB::table('usuario as u')
					->select('u.usuaid','u.persid','u.usuanombre','u.usuaapellidos','u.usuaemail','u.usuanick',
                            'u.usuaalias','p.tipideid','p.persdocumento')
					->join('persona as p', 'p.persid', '=', 'u.persid')
					->where('u.usuaid', Auth::id())->first(); 
		$tipoidentificaciones = DB::table('tipoidentificacion')->get();
        return response()->json(['success' => true,"usuario" => $usuario,'tipoidentificaciones' => $tipoidentificaciones]);
	}

    //Funcion para actualizar los datos
  	public function updatePerfil(Request $request)
  	{
        $usuario = User::findOrFail(Auth::id());
   		$this->validate(request(),[
            'nombre'             => 'required|string|min:5|max:50',
            'apellido'           => 'required|string|min:5|max:50',
            'usuario'            => 'required|string|min:6|max:15|unique:usuario,usuanick,'.$usuario->usuaid.',usuaid',
            'correo'             => 'required|email|string|unique:usuario,usuaemail,'.$usuario->usuaid.',usuaid'
           ]);

        try {
            $usuario->usuanombre    = mb_strtoupper($request->nombre,'UTF-8');
            $usuario->usuaapellidos = mb_strtoupper($request->apellido,'UTF-8');
            $usuario->usuanick      = mb_strtoupper($request->usuario,'UTF-8'); 
            $usuario->usuaemail     = $request->correo;
            $usuario->save();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            return response()->json(['success' => true, 'message' => 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    //Funcion para actualizar los datos
	public function updatePassword(Request $request)
	{
		$this->validate(request(),[
			'password'    => 'required|string|min:6',
			'repPassword' => 'required|confirmed',
			'repPassword' => 'required|same:password'
		]);

		$generales               = new generales();
		list($success, $message) = $generales->validarContrasena($request->password);
		if(!$success){
			return response()->json(['success' => false, 'message'=> $message]);
		}

		//Verifico que la contraseña no la halla utilizado el usuario
		$historialContrasenas = DB::table('historialcontrasena')->select('hisconid','hisconpassword')->where('usuaid', Auth::id())->get();
		foreach($historialContrasenas as $historialContrasena){
			if (password_verify($request->password, $historialContrasena->hisconpassword)) {
				return response()->json(['success' => false, 'message'=> 'Lo siento, pero esta contraseña ya ha sido utilizada en el pasado. Por favor, elige una contraseña diferente']);
			}
		}

		DB::beginTransaction();
		try {
			$historialcontrasena                 = new HistorialContrasena();
			$historialcontrasena->usuaid         = Auth::id();
			$historialcontrasena->hisconpassword = bcrypt($request->password);
			$historialcontrasena->save();

			$usuario                      = User::findOrFail(Auth::id());
			$usuario->password            = bcrypt($request->password);
			$usuario->usuacambiarpassword = false;
			$usuario->save();
			DB::commit();
			return response()->json(['success' => true, 'message' => 'Contraseña modificada con éxito por favor cierra sesión y vuelve a ingresar al sistema']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}