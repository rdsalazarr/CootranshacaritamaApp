<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\EncomiendaCambioEstado;
use App\Models\Despacho\PlanillaRuta;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB, Auth;
use App\Util\generarPdf;
use App\Util\generales;
use Carbon\Carbon;

class PlanillaRutaController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['estado' => 'required']);

        $data   = DB::table('planillaruta as pr')
                    ->select('pr.plarutid','pr.rutaid','pr.vehiid','pr.condid', 'pr.plarutfechahorasalida',
                    'pr.plarutfechahoraregistro as fechaHoraRegistro','pr.plarutfechahorasalida as fechaHoraSalida',
                    'mo.muninombre as municipioOrigen', 'md.muninombre as municipioDestino',
                    DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                    DB::raw("CONCAT('1', LPAD(pr.agenid, 2, '0'), '-', pr.plarutconsecutivo) as numeroPlanilla"),
                    DB::raw("CONCAT(p.persprimernombre,' ',  p.persprimerapellido) as nombreConductor"),
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
                    ->join('conductor as c', 'c.condid', '=', 'pr.condid')
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
                                    ->where('v.agenid', auth()->user()->agenid)->get();

        $conductores    = DB::table('conductorvehiculo as cv')
                                ->select('c.condid','cv.vehiid', DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                    p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"))
                                ->join('conductor as c', 'c.condid', '=', 'cv.condid')
                                ->join('persona as p', 'p.persid', '=', 'c.persid')
                                ->where('c.tiescoid', 'A')->get();

        $rutas          = DB::table('ruta as r')
                                ->select('r.rutaid',DB::raw("CONCAT(mo.muninombre,' - ', md.muninombre) as nombreRuta"))
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

        $fechaActual = Carbon::now()->format('Y-m-d h:m:s');

        return response()->json(["fechaActual" => $fechaActual, "vehiculos" => $vehiculos, "conductores" => $conductores,  "rutas" => $rutas]);
    }

    public function salve(Request $request)
	{
        $plarutid     = $request->codigo;
	    $planillaruta = ($plarutid != 000) ? PlanillaRuta::findOrFail($plarutid) : new PlanillaRuta(); 

	    $this->validate(request(),[	
                'ruta'            => 'required|numeric',
                'vehiculo'        => 'required|numeric',
                'conductor'       => 'required|numeric',
                'fechaHoraSalida' => 'required|date|date_format:Y-m-d H:i:s',
	        ]);

        DB::beginTransaction();
        try {
            $fechaHoraActual                       = Carbon::now();
            if($request->tipo === 'I'){
                $planillaruta->agenid                  = auth()->user()->agenid;
                $planillaruta->usuaidregistra          = Auth::id();
                $planillaruta->plarutfechahoraregistro = $fechaHoraActual;
                $planillaruta->plarutconsecutivo       = $this->obtenerConsecutivo(); 
            }

            $planillaruta->rutaid                  = $request->ruta;
			$planillaruta->vehiid                  = $request->vehiculo;
			$planillaruta->condid                  = $request->conductor;
            $planillaruta->plarutfechahorasalida   = $request->fechaHoraSalida;
           	$planillaruta->save();    
   
            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function consultarDatos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required']);

        $planillaRuta   = DB::table('planillaruta as pr')
                        ->select('pr.plarutfechahoraregistro','pr.plarutfechahorasalida',
                        DB::raw("CONCAT(mo.muninombre,' - ', md.muninombre) as nombreRuta"),
                        DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                        DB::raw("CONCAT('1', LPAD(pr.agenid, 2, '0'), '-', pr.plarutconsecutivo) as numeroPlanilla"),
                        DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                    p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"),
                        DB::raw('(SELECT COUNT(encoid) AS encoid FROM encomienda WHERE plarutid = pr.plarutid) AS totalEncomiendas'))
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
                        ->join('conductor as c', 'c.condid', '=', 'pr.condid')
                        ->join('persona as p', 'p.persid', '=', 'c.persid')
                        ->join('vehiculo as v', 'v.vehiid', '=', 'pr.vehiid')
                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                        ->where('pr.plarutid', $request->codigo)
                        ->first();

        return response()->json(["planillaRuta" => $planillaRuta]);    
    }
    
    public function registrarSalida(Request $request)
	{
        $this->validate(request(),['codigo' => 'required', 'conductor' => 'required', 'vehiculo' => 'required']);
        try {
            $fechaHoraActual = Carbon::now();

            //Verifico que el conductor y el vehiculo no este suspendido
            $conductor   = DB::table('conductor')->select('condid')->where('tiescoid', 'A')->where('condid', $request->conductor)->first();
            if(!$conductor){
                return response()->json(['success' => false, 'message'=> 'Ocurrio un error al procesar la petición, el conductor no se encuentra activo']);
            }

            $vehiculo   = DB::table('vehiculo')->select('vehiid')->where('tiesveid', 'A')->where('vehiid', $request->vehiculo)->first();
            if(!$vehiculo){
                return response()->json(['success' => false, 'message'=> 'Ocurrio un error al procesar la petición, el vehículo no se encuentra activo']);
            }

            $planillaruta                   = PlanillaRuta::findOrFail($request->codigo);
            $planillaruta->plarutdespachada = true;
           	$planillaruta->save();

            $encomiendas      = DB::table('encomienda')->select('encoid')->where('plarutid', $request->codigo)->get();
            foreach($encomiendas as $encomienda){

                $encomienda           = Encomienda::findOrFail($encomienda->encoid);
                $encomienda->tiesenid = 'T';
                $encomienda->save();

                $encomiendacambioestado 				   = new EncomiendaCambioEstado();
                $encomiendacambioestado->encoid            = $encomienda->encoid;
                $encomiendacambioestado->tiesenid          = 'T';
                $encomiendacambioestado->encaesusuaid      = Auth::id();
                $encomiendacambioestado->encaesfechahora   = $fechaHoraActual;
                $encomiendacambioestado->encaesobservacion = 'En transporte hacia el terminal destino. Proceso realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $encomiendacambioestado->save();
            }

        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function verFactura(Request $request)
    {
		$this->validate(request(),['codigo'   => 'required']);
		try{

            $generarPdf   = new generarPdf();
            $arrayDatos   = [
                                "fechaPlanilla"      => '2023-11-27',
                                "numeroPlanilla"     => '101-084043',
                                "fechaSalida"        => '2023-11-27',
                                "horaSalida"         => '05:30',
                                "nombreRuta"         => '007 - OCANA - ABREGO',
                                "numeroVehiculo"     => '437',
                                "placaVehiculo"      => 'UVG039',
                                "conductorVehiculo"  => 'JORGE EMIRO RUEDA SANGUINO',
                                "documentoConductor" => '88283517',
                                "telefonoConductor"  => '3166147490',
                                "valorEncomienda"    => '$ 0',
                                "valorDomicilio"     => '$ 0',
                                "valorComision"      => '$ 0',
                                "valorTotal"         => '$ 0',
                                "numeroOperacion"    => '568675',
                                "usuarioElabora"     => 'NIXSON RIOS',
                                "usuarioDespacha"    => 'KAREN YESENIA CONTRERAS JIMENE',
                                "direccionAgencia"   => 'PARQUE PRINCIPAL',
                                "telefonoAgencia"    => '3142154286',
                                "mensajePlanilla"    => '*** FELIZ VIAJE ***',
                                "metodo" => 'S'
                            ];
            $data         = $generarPdf->planillaServicioTransporte($arrayDatos);
  			return response()->json(["data" => $data ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }
	}

    public function obtenerConsecutivo()
	{
        $consecutivoPlanilla = DB::table('planillaruta')->select('plarutconsecutivo as consecutivo')->orderBy('plarutid', 'desc')->first();
        $consecutivo = ($consecutivoPlanilla === null) ? 1 : $consecutivoPlanilla->consecutivo + 1;
        return str_pad($consecutivo,  6, "0", STR_PAD_LEFT);
    }
}