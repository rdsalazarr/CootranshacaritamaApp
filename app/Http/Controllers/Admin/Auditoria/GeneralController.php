<?php

namespace App\Http\Controllers\Admin\Auditoria;

use App\Http\Controllers\Controller;
use Exception, Auth, DB, File;
use Illuminate\Http\Request;
use App\Util\generales;
use Carbon\Carbon;

class GeneralController extends Controller
{
    public function index()
	{
		try{
			$data = DB::table('usuario as u')
						->select('u.usuaid','u.persid','p.tipideid','p.persdocumento','u.usuanombre','u.usuaapellidos','u.usuaalias',
								'u.usuanick','u.usuaemail','u.usuabloqueado','u.usuaactivo','u.usuacambiarpassword', 'u.agenid','u.cajaid','c.cajanumero',
								DB::raw("CONCAT(ti.tipidesigla,'-', p.persdocumento ) as tipoDocumento"),
								DB::raw("if(u.usuaactivo = 1,'Sí', 'No') as estado"),
								DB::raw("if(u.usuabloqueado = 1,'Sí', 'No') as bloqueado"),
								DB::raw("if(u.usuacambiarpassword = 1,'Sí', 'No') as cambiarpassword"))
						->join('persona as p', 'p.persid', '=', 'u.persid')
						->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
						->leftJoin('caja as c', 'c.cajaid', '=', 'u.cajaid')
						->whereNotIn('u.usuaid', [1])
						->orderBy('u.usuanombre')->orderBy('u.usuaapellidos')->get();
		
			return response()->json(['success' => true, "data" => $data]);
		}catch(Exception $e){
			return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
		}
	}

	public function showUsuario(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);

		try{
			$data = DB::table('usuario as u')
						->select(DB::raw("CONCAT(ti.tipidesigla,'-', p.persdocumento ) as tipoDocumento"),
						DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"),'u.usuanick',
						"is.ingsisipacceso","is.ingsisfechahoraingreso","is.ingsisfechahorasalida")
						->join('persona as p', 'p.persid', '=', 'u.persid')
						->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
						->join('ingresosistema as is', 'is.usuaid', '=', 'u.usuaid')
						->where('u.usuaid', $request->codigo)->get();
		
			return response()->json(['success' => true, "data" => $data]);
		}catch(Exception $e){
			return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
		}
	}
}