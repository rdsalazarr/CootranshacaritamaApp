<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HistorialContrasena;
use App\Models\Menu\Funcionalidad;
use Illuminate\Http\Request;
use Exception, DB, Auth;
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

	public function generarMenu()
	{
		return response()->json(["data" => Funcionalidad::menus()]);
	}
}