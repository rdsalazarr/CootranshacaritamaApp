<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\HistorialContrasena;
use Illuminate\Http\Request;
use App\Util\generales;
use App\Models\User;
use Carbon\Carbon;
use DB, Auth;

class DashboardController extends Controller
{
    public function index()
	{
		return view('home.dashboard',['title' => 'Dashboard APP']);
	}

	public function dataUsuario()
	{ 
		$dataUsuario = ['nombreCompleto' => Auth::user()->usuanombre.' '.Auth::user()->usuaapellidos,
						'usuanick'       => Auth::user()->usuanick
						];
		return response()->json(["dataUsuario" => $dataUsuario]);
	}
	
    public function reset()
	{   
		return view('home.reset',['title' => 'Modificar credenciales de acceso al APP']);
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
		$historialcontrasenas = DB::table('historialcontrasena')
								->select('hisconid','hisconpassword')
								->where('usuaid', Auth::id())->get();
		foreach($historialcontrasenas as $historialcontrasena){
			if (password_verify($request->password, $historialcontrasena->hisconpassword)) {
				return response()->json(['success' => false, 'message'=> 'Lo siento, pero esta contraseña ya ha sido utilizada en el pasado. Por favor, elige una contraseña diferente']);
			}
		}

		DB::beginTransaction();
		try {
			$historialcontrasena                 = new HistorialContrasena();
			$historialcontrasena->usuaid         = Auth::id();
			$historialcontrasena->hisconpassword = bcrypt($request->password);
			$historialcontrasena->save();

			$usuario = User::findOrFail(Auth::id());
			$usuario->password = bcrypt($request->password);
			$usuario->usuacambiarpassword = false;
			$usuario->save();
			DB::commit();
			return response()->json(['success' => true, 'message' => 'Contraseña modificada con éxito. Por favor, espera unos momentos mientras el sistema te redirecciona']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}	
}