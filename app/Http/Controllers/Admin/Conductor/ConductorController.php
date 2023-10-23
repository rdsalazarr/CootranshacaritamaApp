<?php

namespace App\Http\Controllers\Admin\Conductor;

use App\Http\Requests\PersonaRequests;
use App\Http\Controllers\Controller;
use App\Models\Conductor\Conductor;
use Illuminate\Http\Request;
use App\Util\personaManager;
use Exception, DB;

class ConductorController extends Controller
{
    public function index()
    {
        $data = DB::table('persona as p')->select('c.condid', 'p.persid','p.persdocumento', 'p.persdireccion','p.perscorreoelectronico',
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as tipoIdentificacion"),'tec.tiesconombre as estado')
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->join('conductor as c', 'c.persid', '=', 'p.persid')
                                    ->join('tipoestadoconductor as tec', 'tec.tiescoid', '=', 'c.tiescoid')
                                    ->where('c.tiescoid', 'A')
                                    ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                    ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();

        return response()->json(["data" => $data]);
    }

    public function salve(PersonaRequests $request)
	{
        $personaManager = new personaManager();
		return $personaManager->registrar($request);
	}

    public function destroy(Request $request)
	{
		$dependencia        = DB::table('dependencia')->select('depeid')->where('depejefeid', $request->codigo)->first();
        $dependenciapersona = DB::table('dependenciapersona')->select('depperid')->where('depperpersid', $request->codigo)->first();

		if($dependencia || $dependenciapersona){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una dependencia del sistema']);
		}else{
			try {
				$asociado = Conductor::findOrFail($request->codigo);
				$asociado->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}