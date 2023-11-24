<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Http\Controllers\Controller;
use App\Models\Despacho\PlanillaRuta;
use Illuminate\Http\Request;
use Exception, DB, Auth;
use App\Util\generales;
use Carbon\Carbon;

class PlanillaRutaController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['estado' => 'required']);

        $data   = DB::table('planillaruta as pr')
                    ->select('pr.plarutid','pr.plarutfechahoraregistro as fechaHoraRegistro','pr.plarutfechahorasalida as fechaHoraSalida',
                    'mo.muninombre as municipioOrigen', 'md.muninombre as municipioDestino',
                    DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                    DB::raw("CONCAT('1', LPAD(pr.agenid, 2, '0'), '-', pr.plarutconsecutivo) as numeroPlanilla"),
                    DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"),
                    DB::raw("CONCAT(ur.usuanombre,' ',ur.usuaapellidos)  as usuarioRegistra"),
                    DB::raw("CONCAT(urg.usuanombre,' ',urg.usuaapellidos)  as usuarioRecibe"))
                    ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                    ->join('municipio as mo', function($join)
                    {
                        $join->on('mo.munidepaid', '=', 'r.depaidorigen');
                        $join->on('mo.muniid', '=', 'r.muniidorigen');
                    })
                    ->join('municipio as md', function($join)
                    {
                        $join->on('md.munidepaid', '=', 'r.depaiddestino');
                        $join->on('md.muniid', '=', 'r.muniiddestino');
                    })
                    ->join('conductor as c', 'c.condid', '=', 'av.condid')
                    ->join('persona as p', 'p.persid', '=', 'c.persid')
                    ->join('vehiculo as v', 'v.vehiid', '=', 'pr.vehiid')
                    ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                    ->join('usuario as ur', 'ur.usuaid', '=', 'pr.usuaidregistra')
                    ->leftJoin('usuario as urg', 'urg.usuaid', '=', 'pr.usuaidrecibe')
                    ->where('pr.agenid', auth()->user()->agenid) 
                    ->where('pr.plarutdespachada', $request->estado)
                    ->orderBy('pr.plarutid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        $vehiculos      = DB::table('vehiculo as v')
                                    ->select('v.vehiid', DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
                                    ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                    ->where('a.agenid', auth()->user()->agenid)->get();

        $conductores    = DB::table('conductorvehiculo as cv')
                                ->select('cv.vehiid','cv.convehid', 'p.persid', DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                    p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"))
                                ->join('conductor as c', 'c.condid', '=', 'cv.condid')
                                ->join('persona as p', 'p.persid', '=', 'c.persid')
                                ->where('c.tiescoid', 'A')->get();

        $rutas          = DB::table('ruta as r')
                                 ->select('r.rutaid','mo.muninombre as municipioOrigen', 'md.muninombre as municipioDestino')
                                ->join('municipio as mo', function($join)
                                {
                                    $join->on('mo.munidepaid', '=', 'r.depaidorigen');
                                    $join->on('mo.muniid', '=', 'r.muniidorigen');
                                })
                                ->join('municipio as md', function($join)
                                {
                                    $join->on('md.munidepaid', '=', 'r.depaiddestino');
                                    $join->on('md.muniid', '=', 'r.muniiddestino');
                                })->get();
     
        $planillaruta   = [];

        if($request->tipo === 'U'){
            $planillaruta  = DB::table('planillaruta')
                                ->select('agenid','rutaid','vehiid','condid','usuaidregistra','usuaidrecibe','plarutfechahoraregistro',
                                'plarutconsecutivo','plarutfechahorasalida','plarutfechahorarecibe','plarutgenerada')
                                ->where('plarutid', $request->codigo)->first();
        }

        return response()->json(["vehiculos" => $vehiculos, "conductores"   => $conductores, 
                                "rutas"      => $rutas,      "planillaruta" => $planillaruta]);
    }

    public function salve(Request $request)
	{
        $encoid       = $request->codigo;
	    $planillaruta = ($encoid != 000) ? PlanillaRuta::findOrFail($encoid) : new PlanillaRuta(); 

	    $this->validate(request(),[	
                'ruta'            => 'required|numeric',
                'vehiculo'        => 'required|numeric',
                'conductor'       => 'required|numeric',
                'fechaHoraSalida' => 'required|date_format:H:i',
	        ]);

        DB::beginTransaction();
        try { 
            $fechaHoraActual                       = Carbon::now();
			$planillaruta->agenid                  = auth()->user()->agenid;
			$planillaruta->rutaid                  = $request->ruta;
			$planillaruta->vehiid                  = $request->vehiculo;
			$planillaruta->condid                  = $request->conductor;
			$planillaruta->usuaidregistra          = Auth::id();
            $planillaruta->plarutfechahoraregistro = $fechaHoraActual;
			$planillaruta->plarutconsecutivo       = $this->obtenerConsecutivo();
            $planillaruta->plarutfechahorasalida   = $request->fechaHoraSalida;
           	$planillaruta->save();

            if($request->tipo === 'I'){
                $planillaConsecutivo = PlanillaRuta::latest('plarutid')->first();
				$plarutid            = $planillaConsecutivo->plarutid;
            }
   
            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', 'planillaId' => $plarutid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function obtenerConsecutivo()
	{
        $consecutivoPlanilla = DB::table('planillaruta')->select('plarutconsecutivo as consecutivo')->orderBy('plarutid', 'desc')->first();
        $consecutivo = ($consecutivoPlanilla === null) ? 1 : $consecutivoPlanilla->consecutivo + 1;
        return str_pad($consecutivo,  6, "0", STR_PAD_LEFT);
    }
}