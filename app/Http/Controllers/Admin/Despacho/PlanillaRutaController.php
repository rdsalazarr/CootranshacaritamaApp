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
                    DB::raw("CONCAT(urg.usuanombre,' ',urg.usuaapellidos)  as usuarioDespacha"))
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
                    ->leftJoin('usuario as urg', 'urg.usuaid', '=', 'pr.usuaiddespacha')
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
            $fechaHoraActual                           = Carbon::now();
            if($request->tipo === 'I'){
                $anioActual                            = $fechaHoraActual->year;
                $planillaruta->agenid                  = auth()->user()->agenid;
                $planillaruta->usuaidregistra          = Auth::id();
                $planillaruta->plarutfechahoraregistro = $fechaHoraActual;
                $planillaruta->plarutanio              = $anioActual;
                $planillaruta->plarutconsecutivo       = $this->obtenerConsecutivo($anioActual); 
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
            $planillaruta->usuaiddespacha   = Auth::id();
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

    public function verPlanilla(Request $request)
    {
		$this->validate(request(),['codigo'   => 'required']);
		try{

            $planillaruta  = DB::table('planillaruta as pr')
                            ->select('pr.plarutfechahoraregistro','pr.plarutfechahorasalida','v.vehiplaca','v.vehinumerointerno','p.persdocumento',
                            'p.persnumerocelular','a.agennombre', 'a.agendireccion',
                            DB::raw("CONCAT(pr.plarutanio,'',pr.plarutconsecutivo) as consecutivoPlanilla"),         
                            DB::raw("CONCAT(mo.muninombre,' - ', md.muninombre) as nombreRuta"),                            
                            DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"),
                            DB::raw("CONCAT(ur.usuanombre,' ',ur.usuaapellidos) as usuarioRegistra"),
                            DB::raw("CONCAT(urd.usuanombre,' ',urd.usuaapellidos) as usuarioDespahcca"),                            
                            DB::raw("CONCAT(a.agentelefonocelular, if(a.agentelefonofijo is null ,'', ' - '), a.agentelefonofijo) as telefonoAgencia"),
                            DB::raw("(SELECT menimpvalor FROM mensajeimpresion WHERE menimpnombre = 'PLANILLA') AS mensajePlanilla"),
                            DB::raw('(SELECT SUM(encovalorenvio) AS encovalorenvio FROM encomienda WHERE plarutid = pr.plarutid) AS valorEnvio'),
                            DB::raw('(SELECT SUM(encovalordomicilio) AS encovalordomicilio FROM encomienda WHERE plarutid = pr.plarutid) AS valorDomicilioEnvio'),
                            DB::raw('(SELECT SUM(encovalorcomisionseguro) AS encovalorcomisionseguro FROM encomienda WHERE plarutid = pr.plarutid) AS valorComisionEnvio'),
                            DB::raw('(SELECT SUM(encovalortotal) AS encovalortotal FROM encomienda WHERE plarutid = pr.plarutid) AS valorTotalEnvio'),
                            DB::raw('(SELECT SUM(tiquvalortiquete) AS tiquvalortiquete FROM tiquete WHERE plarutid = pr.plarutid) AS subTotalTiquete'),
                            DB::raw('(SELECT SUM(tiquvalorfondoreposicion) AS tiquvalorfondoreposicion FROM tiquete WHERE plarutid = pr.plarutid) AS valorFondoReposicion'),
                            DB::raw('(SELECT SUM(tiquvalortotal) AS tiquvalortotal FROM tiquete WHERE plarutid = pr.plarutid) AS valorTotalTiquete'),
                            DB::raw('(SELECT SUM(tiqucantidad) AS tiqucantidad FROM tiquete WHERE plarutid = pr.plarutid) AS cantidadPasajeros'))
                            ->join('conductor as c', 'c.condid', '=', 'pr.condid')
                            ->join('persona as p', 'p.persid', '=', 'c.persid')
                            ->join('vehiculo as v', 'v.vehiid', '=', 'pr.vehiid')
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
                            ->join('agencia as a', 'a.agenid', '=', 'pr.agenid')
                            ->join('usuario as ur', 'ur.usuaid', '=', 'pr.usuaidregistra')
                            ->leftJoin('usuario as urd', 'urd.usuaid', '=', 'pr.usuaiddespacha')
                            ->where('pr.plarutid', $request->codigo)->first();

            $tiquetes  = DB::table('tiquete as t')
                            ->select('t.tiquvalortotal as totalTiquete', DB::raw("CONCAT('1', LPAD(pr.agenid, 2, '0'), t.tiquanio, t.tiquconsecutivo) as numeroTiquete"),
                            'tp.tiqpuenumeropuesto', 'mde.muninombre as municipioDestino', 
                            DB::raw("CONCAT(ps.perserprimernombre,' ', ps.perserprimerapellido) as nombreCliente"))
                            ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                            ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
                            ->join('tiquetepuesto as tp', 'tp.tiquid', '=', 't.tiquid')
                            ->join('municipio as mde', function($join)
                            {
                                $join->on('mde.munidepaid', '=', 't.depaiddestino');
                                $join->on('mde.muniid', '=', 't.muniiddestino');
                            })
                            ->where('pr.plarutid', $request->codigo)->first();

            $agencias  = DB::table('agencia as a')->select('a.agennombre',
                            DB::raw("CONCAT('OFI. ',a.agennombre,':') as nombreAgencia"),
                            DB::raw('(SELECT SUM(tiquvalorfondoreposicion) AS tiquvalorfondoreposicion FROM tiquete WHERE plarutid = pr.plarutid) AS totalFondoReposicion'))
                            ->join('planillaruta as pr', 'pr.plarutid', '=', 't.agenid')
                            ->where('pr.plarutid', $request->codigo)->first();

            $generarPdf = new generarPdf();
            $arrayDatos = [
                            "fechaPlanilla"        => $planillaruta->plarutfechahoraregistro,
                            "numeroPlanilla"       => $planillaruta->consecutivoPlanilla,
                            "fechaSalida"          => substr($planillaruta->plarutfechahorasalida, 0, -9),        
                            "horaSalida"           => substr($planillaruta->plarutfechahorasalida, -8, 10),
                            "nombreRuta"           => $planillaruta->nombreRuta,
                            "numeroVehiculo"       => $planillaruta->vehinumerointerno,
                            "placaVehiculo"        => $planillaruta->vehiplaca,
                            "conductorVehiculo"    => $planillaruta->nombreConductor,
                            "documentoConductor"   => $planillaruta->persdocumento,
                            "telefonoConductor"    => $planillaruta->persnumerocelular,
                            "valorEncomienda"      => $planillaruta->valorEnvio,
                            "valorDomicilio"       => $planillaruta->valorDomicilioEnvio,
                            "valorComision"        => $planillaruta->valorComisionEnvio,
                            "valorTotal"           => $planillaruta->valorTotalEnvio,
                            "numeroOperacion"      => '00',//568675
                            "subTotalTiquete"      => $planillaruta->subTotalTiquete,
                            "valorFondoReposicion" => $planillaruta->valorFondoReposicion,
                            "valorTotalTiquete"    => $planillaruta->valorTotalTiquete,
                            "cantidadPasajeros"    => $planillaruta->cantidadPasajeros,
                            "usuarioElabora"       => $planillaruta->usuarioRegistra,
                            "usuarioDespacha"      => $planillaruta->usuarioDespahcca,
                            "nombreAgencia"        => $planillaruta->agennombre,
                            "direccionAgencia"     => $planillaruta->agendireccion,
                            "telefonoAgencia"      => $planillaruta->telefonoAgencia,
                            "mensajePlanilla"      => $planillaruta->mensajePlanilla,
                            "metodo"               => 'S'
                        ];

            $data       = $generarPdf->planillaServicioTransporte($arrayDatos, $tiquetes, $agencias);
  			return response()->json(["data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }
	}

    public function obtenerConsecutivo($anioActual)
	{
        $consecutivoPlanilla = DB::table('planillaruta')->select('plarutconsecutivo as consecutivo')
                                                       // ->where('plarutanio', $anioActual)
                                                        ->where('agenid', auth()->user()->agenid)
                                                        ->orderBy('tiquid', 'Desc')->orderBy('plarutid', 'desc')->first();
        $consecutivo = ($consecutivoPlanilla === null) ? 1 : $consecutivoPlanilla->consecutivo + 1;
        return str_pad($consecutivo,  6, "0", STR_PAD_LEFT);
    }
}