<?php

namespace App\Http\Controllers\Admin;

use Exception, DB, Auth, URL, File;
use App\Http\Controllers\Controller;
use App\Models\HistorialContrasena;
use App\Models\Menu\Funcionalidad;
use Illuminate\Http\Request;
use App\Util\generales;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
	{
		return view('home.dashboard',['title' => 'Dashboard ERP']);
	}
	
    public function reset()
	{ 
		return view('home.reset',['title' => 'Modificar credenciales de acceso al ERP']);
	}
	
	public function welcome()
	{
		$empresa = DB::table('empresa')->select('emprsigla')->where('emprid', 1 )->first();
		$data    = ['nombreUsuario' => ucfirst(mb_strtolower(Auth::user()->usuanombre,'UTF-8')),
					'siglaEmpresa'  => $empresa->emprsigla
					];
		return response()->json(["data" => $data]);
	}

	public function informacion()
	{
		try{
			$imagen  = '';
			$url     = URL::to('/');
			$usuario = DB::table('usuario as u')
							->select(DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 'p.persrutafoto',
							DB::raw("CONCAT(p.persdocumento,'/',p.persrutafoto ) as fotografia"))
							->join('persona as p', 'p.persid', '=', 'u.persid')
							->where('usuaid', Auth::id())->first();

			if($usuario->persrutafoto !== null){
				$ruta   = public_path().'/archivos/persona/'.$usuario->fotografia;
				$imagen = (file_exists($ruta)) ? base64_encode(file_get_contents($ruta)) : '';
			}
	
			return response()->json(['success' => true, "data" => $usuario, "fotografia" => $imagen]);
		}catch(Exception $e){
			return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
		}
	}

	public function generarMenu()
	{
		return response()->json(["data" => Funcionalidad::menus()]);
	}	
}