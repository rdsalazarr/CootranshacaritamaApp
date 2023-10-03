<?php

namespace App\Http\Controllers\Admin\Archivo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class HistoricoController extends Controller
{
    public function index()
	{
        dd("hola");
        $data = DB::table('archivohistorico as ah')
                    ->select('ah.archisid','td.tipdocnombre as tipoDocumental','tea.tiesarnombre as estante','tcu.ticaubnombre as caja','tcb.ticrubnombre as carpeta',
                    'ah.archisnumerofolio as numerofolio','ah.archisasuntodocumento as asunto')
                    ->join('tipodocumental as td', 'td.tipdocid', '=', 'ah.tipdocid')
                    ->join('tipoestantearchivador as tea', 'tea.tiesarid', '=', 'ah.tiesarid')
					->join('tipocajaubicacion as tcu', 'tcu.ticaubid', '=', 'ah.ticaubid')
                    ->join('tipocarpetaubicacion as tcb', 'tcb.ticrubid', '=', 'ah.ticrubid')
                    ->get();

        dd($data);
        
		return response()->json(['success' => true, "data" => $data]);
	}

    public function datos(Request $request)
	{
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required']);	
		$codigo            = $request->codigo;
		$tipo              = $request->tipo;
        $data              = [];
        $digitalizados     = [];
        if($tipo === 'U'){
            $data   = DB::table('radicaciondocumentoentrante as rde')
                        ->select('rde.peradoid','rde.tipmedid','rde.tierdeid','rde.depaid','rde.muniid','rde.depeid','rde.radoenfechadocumento',
                                'rde.radoenfechallegada','rde.radoenpersonaentregadocumento','rde.radoenasunto','rde.radoentieneanexo',
                                'rde.radoendescripcionanexo','rde.radoentienecopia','rde.radoenobservacion',
                                'prd.tipideid','prd.peradodocumento','prd.peradoprimernombre','prd.peradosegundonombre','prd.peradoprimerapellido',
                                'prd.peradosegundoapellido', 'prd.peradodireccion','prd.peradotelefono','prd.peradocorreo','prd.peradocodigodocumental',
                                DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid) AS totalCopias'),
                                DB::raw('(SELECT COUNT(radoeaid) AS radoeaid FROM radicaciondocentanexo WHERE radoenid = rde.radoenid AND radoearequiereradicado = false ) AS totalAnexos'))
                        ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                        ->where('rde.radoenid', $codigo)->first();            
        }
 
        $tipodocumentales        = DB::table('tipodocumental')->select('tipdocid','tipdocnombre')->orderBy('tipdocnombre')->get();
		$tipoestantearchivadores = DB::table('tipoestantearchivador')->select('tiesarid','tiesarnombre')->where('tiesaractivo', true)->orderBy('tiesarnombre')->get();
        $tipocajaubicaciones     = DB::table('tipocajaubicacion')->select('ticaubid','ticaubnombre')->orderBy('ticaubnombre')->get();
        $tipocarpetaubicaciones  = DB::table('tipocarpetaubicacion')->select('ticrubid','ticrubnombre')->orderBy('ticrubnombre')->get();

        return response()->json(["tipodocumentales" => $tipodocumentales, "tipoestantearchivadores" => $tipoestantearchivadores,  "tipocajaubicaciones"    => $tipocajaubicaciones,        
								"data"              => $data,             "digitalizados"           => $digitalizados]);
	}
}