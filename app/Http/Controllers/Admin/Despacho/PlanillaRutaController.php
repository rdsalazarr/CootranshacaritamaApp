<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\EncomiendaCambioEstado;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Models\Despacho\PlanillaRuta;
use App\Http\Controllers\Controller;
use App\Models\Despacho\Encomienda;
use App\Models\Caja\MovimientoCaja;
use App\Models\Caja\CuentaContable;
use App\Models\Despacho\Tiquete;
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

        try{
            $data   = DB::table('planillaruta as pr')
                        ->select('pr.plarutid','pr.rutaid','pr.vehiid','pr.condid', 'pr.plarutfechahorasalida',
                        'pr.plarutfechahoraregistro as fechaHoraRegistro','pr.plarutfechahorasalida as fechaHoraSalida',
                        'mo.muninombre as municipioOrigen', 'md.muninombre as municipioDestino',
                        DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                        DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo) as numeroPlanilla"),
                        DB::raw("CONCAT(p.persprimernombre,' ',  p.persprimerapellido) as nombreConductor"),
                        DB::raw("CONCAT(ur.usuanombre,' ',ur.usuaapellidos) as usuarioRegistra"),
                        DB::raw("CONCAT(urg.usuanombre,' ',urg.usuaapellidos) as usuarioDespacha"))
                        ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                        ->join('municipio as mo', function($join)
                        {
                            $join->on('mo.munidepaid', '=', 'r.rutadepaidorigen');
                            $join->on('mo.muniid', '=', 'r.rutamuniidorigen');
                        })
                        ->join('municipio as md', function($join)
                        {
                            $join->on('md.munidepaid', '=', 'r.rutadepaiddestino');
                            $join->on('md.muniid', '=', 'r.rutamuniiddestino');
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

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        try{
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
                                        $join->on('mo.munidepaid', '=', 'r.rutadepaidorigen');
                                        $join->on('mo.muniid', '=', 'r.rutamuniidorigen');
                                    })
                                    ->join('municipio as md', function($join)
                                    {
                                        $join->on('md.munidepaid', '=', 'r.rutadepaiddestino');
                                        $join->on('md.muniid', '=', 'r.rutamuniiddestino');
                                    })->get();

            $fechaActual = Carbon::now()->format('Y-m-d h:m:s');

            return response()->json(['success' => true, "fechaActual" => $fechaActual, "vehiculos" => $vehiculos, "conductores" => $conductores,  "rutas" => $rutas]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
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

        try{
            $planillaRuta   = DB::table('planillaruta as pr')
                            ->select('pr.plarutfechahoraregistro','pr.plarutfechahorasalida',
                            DB::raw("CONCAT(mo.muninombre,' - ', md.muninombre) as nombreRuta"),
                            DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                            DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo) as numeroPlanilla"),
                            DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"),
                            DB::raw('(SELECT COUNT(encoid) AS encoid FROM encomienda WHERE plarutid = pr.plarutid) AS totalEncomiendas'),
                            DB::raw('(SELECT COUNT(tiquid) AS encotiquidid FROM tiquete WHERE plarutid = pr.plarutid) AS totalTiquete'))
                            ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                            ->join('municipio as mo', function($join)
                            {
                                $join->on('mo.munidepaid', '=', 'r.rutadepaidorigen');
                                $join->on('mo.muniid', '=', 'r.rutamuniidorigen');
                            })
                            ->join('municipio as md', function($join)
                            {
                                $join->on('md.munidepaid', '=', 'r.rutadepaiddestino');
                                $join->on('md.muniid', '=', 'r.rutamuniiddestino');
                            })
                            ->join('conductor as c', 'c.condid', '=', 'pr.condid')
                            ->join('persona as p', 'p.persid', '=', 'c.persid')
                            ->join('vehiculo as v', 'v.vehiid', '=', 'pr.vehiid')
                            ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                            ->where('pr.plarutid', $request->codigo)
                            ->first();

            $encomiendas = [];
            if($planillaRuta->totalEncomiendas > 0){
                $encomiendas = DB::table('encomienda as e')
                                    ->select('te.tipencnombre as tipoEncomienda',
                                            DB::raw("CONCAT(de.depanombre,' - ',md.muninombre) as destinoEncomienda"),
                                            DB::raw("CONCAT('$ ', FORMAT(e.encovalorenvio, 0)) as valorEnvio"),
                                            DB::raw("CONCAT('$ ', FORMAT(e.encovalordeclarado, 0)) as valorDeclarado"),
                                            DB::raw("CONCAT('$ ', FORMAT(e.encovalorcomisionseguro, 0)) as comisionSeguro"),
                                            DB::raw("CONCAT('$ ', FORMAT(e.encovalorcomisionempresa, 0)) as comisionEmpresa"),
                                            DB::raw("CONCAT('$ ', FORMAT(e.encovalorcomisionagencia, 0)) as comisionAgencia"),
                                            DB::raw("CONCAT('$ ', FORMAT(e.encovalorcomisionvehiculo, 0)) as comisionVehiculo"),
                                            DB::raw("CONCAT('$ ', FORMAT(e.encovalortotal, 0)) as valorTotal"),
                                            DB::raw("CONCAT(ps.perserprimernombre,' ',if(ps.persersegundonombre is null ,'', ps.persersegundonombre),' ',
                                                ps.perserprimerapellido,' ',if(ps.persersegundoapellido is null ,' ', ps.persersegundoapellido)) as nombrePersonaRemitente"))
                                    ->join('personaservicio as ps', 'ps.perserid', '=', 'e.perseridremitente')
                                    ->join('tipoencomienda as te', 'te.tipencid', '=', 'e.tipencid')
                                    ->join('departamento as de', 'de.depaid', '=', 'e.encodepaiddestino')
                                    ->join('municipio as md', function($join)
                                    {
                                        $join->on('md.munidepaid', '=', 'e.encodepaiddestino');
                                        $join->on('md.muniid', '=', 'e.encomuniiddestino');
                                    })
                                    ->where('e.plarutid', $request->codigo)->get();
                }

            $tiquetes    = [];
            if($planillaRuta->totalTiquete > 0){
                $tiquetes  = DB::table('tiquete as t')
                            ->select(
                            DB::raw("CONCAT('$ ', FORMAT(t.tiquvalortiquete, 0)) as valorTiquete"),
                            DB::raw("CONCAT('$ ', FORMAT(t.tiquvalordescuento, 0)) as valorDescuento"),
                            DB::raw("CONCAT('$ ', FORMAT(t.tiquvalorseguro, 0)) as valorSeguro"),
                            DB::raw("CONCAT('$ ', FORMAT(t.tiquvalorfondoreposicion, 0)) as valorValorfondoReposicion"),
                            DB::raw("CONCAT('$ ', FORMAT(t.tiquvalortotal, 0)) as valorTotalTiquete"),
                            DB::raw("CONCAT(t.agenid, t.tiquanio, t.tiquconsecutivo) as numeroTiquete"), 'mde.muninombre as municipioDestino', 'a.agennombre as nombreAgencia', 
                            DB::raw("CONCAT(ps.perserprimernombre,' ',IFNULL(ps.persersegundonombre,''),' ',ps.perserprimerapellido,' ',IFNULL(ps.persersegundoapellido,'')) as nombreCliente"))
                            ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                            ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
                            ->join('municipio as mde', function($join)
                            {
                                $join->on('mde.munidepaid', '=', 't.tiqudepaiddestino');
                                $join->on('mde.muniid', '=', 't.tiqumuniiddestino');
                            })
                            ->join('agencia as a', 'a.agenid', '=', 't.agenid')
                            ->where('pr.plarutid', $request->codigo)->get();
            }

            $resumenPlanilla = $this->obtenerResumenPlanilla($request->codigo, true);

            return response()->json(['success' => true, "planillaRuta" => $planillaRuta, "encomiendas"     => $encomiendas, 
                                                        "tiquetes"     => $tiquetes,     "resumenPlanilla" => $resumenPlanilla]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function registrarSalida(Request $request)
	{
        $this->validate(request(),['codigo' => 'required', 'conductor' => 'required', 'vehiculo' => 'required']);

        DB::beginTransaction();
        try {
            $generales            = new generales();
            $fechaHoraActual      = Carbon::now();
            $fechaActual          = $fechaHoraActual->format('Y-m-d');

            //Verifico que no existan tiquete sin contabilizar de otra agencia
            $tiquetesAgencias = DB::table('tiquete as t')->distinct()
                                ->select('t.agenid', 't.usuaid', 'u.cajaid', 'a.agennombre',
                                        DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"),
                                        DB::raw("IFNULL(mc.movcajfechahoraapertura, 'NO') as cajaAbierta"))
                                ->join('usuario as u', 'u.usuaid', '=', 't.usuaid')
                                ->join('agencia as a', 'a.agenid', '=', 'u.agenid')
                                ->leftJoin('movimientocaja as mc', function ($join) use ($fechaActual) {
                                    $join->on('mc.usuaid', '=', 'u.usuaid')
                                        ->on('mc.cajaid', '=', 'u.cajaid')
                                        ->whereDate('mc.movcajfechahoraapertura', $fechaActual)
                                        ->whereNull('mc.movcajsaldofinal');
                                })
                                ->where('t.tiqucontabilizado', 0)
                                ->where('t.plarutid', $request->codigo)
                                ->whereNull('mc.movcajfechahoraapertura')
                                ->get();
            foreach ($tiquetesAgencias as $tiqueteAgencia) {
                $nombreUsuario = $tiqueteAgencia->nombreUsuario;
                $nombreAgencia = $tiqueteAgencia->agennombre;

                if ($tiqueteAgencia->cajaAbierta === 'NO') {
                    return response()->json(['success' => false, 'message'=> 'No es posible proceder con la salida del vehículo. Hemos observado que el usuario '.$nombreUsuario.', perteneciente a la '.$nombreAgencia.', no tiene su caja activa para el día de hoy']);  
                }
            }

            //Verifico que el conductor y el vehiculo no este suspendido
            $conductor   = DB::table('conductor')->select('condid')->where('tiescoid', 'A')->where('condid', $request->conductor)->first();
            if(!$conductor){
                return response()->json(['success' => false, 'message'=> 'Ocurrio un error al procesar la petición, el conductor no se encuentra activo']);
            }

            $vehiculo   = DB::table('vehiculo')->select('vehiid')->where('tiesveid', 'A')->where('vehiid', $request->vehiculo)->first();
            if(!$vehiculo){
                return response()->json(['success' => false, 'message'=> 'Ocurrio un error al procesar la petición, el vehículo no se encuentra activo']);
            }

            //Realizo el proceso indicado
            $planillaruta                   = PlanillaRuta::findOrFail($request->codigo);
            $planillaruta->usuaiddespacha   = Auth::id();
            $planillaruta->plarutdespachada = true;
           	$planillaruta->save();

            $encomiendas      = DB::table('encomienda')->select('encoid')->where('plarutid', $request->codigo)->get();
            /*foreach($encomiendas as $encomienda){

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
            }*/

            //Consulto para contabilizar los tiquete
            $tiquetesContabilizar = DB::table('tiquete as t')
                                    ->select('t.plarutid','t.agenid','t.usuaid', 'u.cajaid', DB::raw('SUM(t.tiquvalortotal) as valorContabilizar'),
                                    DB::raw('SUM(t.tiquvalorfondoreposicion) as valorContabilizarFondoReposicion'),
                                    DB::raw('SUM(t.tiquvalorestampilla) as valorContabilizarEstampilla'),
                                    DB::raw('SUM(t.tiquvalorseguro) as valorContabilizarSeguro'),
                                    DB::raw('SUM(t.tiquvalorfondorecaudo) as valorContabilizarFondoRecaudo'))
                                ->join('usuario as u', 'u.usuaid', '=', 't.usuaid')
                                ->where('t.plarutid', $request->codigo)
                                ->where('t.tiqucontabilizado', 0)
                                ->groupBy('t.plarutid')
                                ->groupBy('t.agenid')
                                ->groupBy('t.usuaid')
                                ->groupBy('u.cajaid')
                                ->get();

            if(count($tiquetesContabilizar) > 0){
                $tiquetes = DB::table('tiquete')->select('tiquid')->where('tiqucontabilizado', 0)->where('plarutid', $request->codigo)->get();
                foreach($tiquetes as $tiqueteEstado){
                    $tiqueteContabilizado                    = Tiquete::findOrFail($tiqueteEstado->tiquid); 
                    $tiqueteContabilizado->tiqucontabilizado = true;
                    $tiqueteContabilizado->save();
                }

                foreach($tiquetesContabilizar as $tiqueteContabilizar){
                    $valorContabilizar    = $generales->redondearCienMasCercano($tiqueteContabilizar->valorContabilizar);
                    $valorFondoReposicion = $generales->redondearCienMasCercano($tiqueteContabilizar->valorContabilizarFondoReposicion);
                    $valorEstampilla      = $generales->redondearCienMasCercano($tiqueteContabilizar->valorContabilizarEstampilla);
                    $valorSeguro          = $generales->redondearCienMasCercano($tiqueteContabilizar->valorContabilizarSeguro);
                    $valorFondoRecaudo    = $generales->redondearCienMasCercano($tiqueteContabilizar->valorContabilizarFondoRecaudo);
                    $valorTiquete         = $generales->redondearCienMasCercano($valorContabilizar - $valorFondoReposicion - $valorEstampilla -  $valorSeguro - $valorFondoRecaudo);
                    $usuarioId            = $tiqueteContabilizar->usuaid;
                    $cajaId               = $tiqueteContabilizar->cajaid;
                    $agenciaId            = $tiqueteContabilizar->agenid;

                    //Se realiza la contabilizacion
                    $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual, $cajaId, $agenciaId, $usuarioId);
                    $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                    $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                    $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('caja');
                    $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                    $comprobantecontabledetalle->cocodemonto     = $valorContabilizar;
                    $comprobantecontabledetalle->save();

                    $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                    $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                    $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('pagoTiquete');
                    $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                    $comprobantecontabledetalle->cocodemonto     = $valorTiquete;
                    $comprobantecontabledetalle->save();

                    if($valorFondoReposicion > 0){
                        $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                        $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                        $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('fondoReposicion');
                        $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                        $comprobantecontabledetalle->cocodemonto     = $valorFondoReposicion;
                        $comprobantecontabledetalle->save();
                    }

                    if($valorEstampilla > 0){
                        $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                        $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                        $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('pagoEstampilla');
                        $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                        $comprobantecontabledetalle->cocodemonto     = $valorEstampilla;
                        $comprobantecontabledetalle->save();
                    }

                    if($valorSeguro > 0){
                        $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                        $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                        $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('pagoSeguro');
                        $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                        $comprobantecontabledetalle->cocodemonto     = $valorSeguro;
                        $comprobantecontabledetalle->save();
                    }

                    if($valorFondoRecaudo > 0){
                        $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                        $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                        $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('valorFondoRecaudo');
                        $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                        $comprobantecontabledetalle->cocodemonto     = $valorFondoRecaudo;
                        $comprobantecontabledetalle->save();
                    }
                }
            }

            //Se realiza la contabilizacion final
            $resumenPlanilla                             = $this->obtenerResumenPlanilla($request->codigo);
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('caja');
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = -$resumenPlanilla->valorEntregar;//Lo ingresamos con movimiento crédito colocando un signo -
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('pagoTiquete');
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = -$resumenPlanilla->valorEntregar;//Lo ingresamos con movimiento crédito colocando un signo -
            $comprobantecontabledetalle->save();

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
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
                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.persprimerapellido,'')) as nombreConductor"),
                                DB::raw("CONCAT(ur.usuanombre,' ',ur.usuaapellidos) as usuarioRegistra"),
                                DB::raw("CONCAT(urd.usuanombre,' ',urd.usuaapellidos) as usuarioDespacha"),
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
                                $join->on('mo.munidepaid', '=', 'r.rutadepaidorigen');
                                $join->on('mo.muniid', '=', 'r.rutamuniidorigen');
                            })
                            ->join('municipio as md', function($join)
                            {
                                $join->on('md.munidepaid', '=', 'r.rutadepaiddestino');
                                $join->on('md.muniid', '=', 'r.rutamuniiddestino');
                            })
                            ->join('agencia as a', 'a.agenid', '=', 'pr.agenid')
                            ->join('usuario as ur', 'ur.usuaid', '=', 'pr.usuaidregistra')
                            ->leftJoin('usuario as urd', 'urd.usuaid', '=', 'pr.usuaiddespacha')
                            ->where('pr.plarutid', $request->codigo)->first();

            $tiquetes  = DB::table('tiquete as t')
                            ->select('t.tiquvalortotal as totalTiquete', DB::raw("CONCAT(t.agenid, t.tiquanio, t.tiquconsecutivo) as numeroTiquete"),
                            'tp.tiqpuenumeropuesto as numeroPuesto', 'mde.muninombre as municipioDestino', 
                            DB::raw("CONCAT(ps.perserprimernombre,' ', ps.perserprimerapellido) as nombreCliente"))
                            ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                            ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
                            ->join('tiquetepuesto as tp', 'tp.tiquid', '=', 't.tiquid')
                            ->join('municipio as mde', function($join)
                            {
                                $join->on('mde.munidepaid', '=', 't.tiqudepaiddestino');
                                $join->on('mde.muniid', '=', 't.tiqumuniiddestino');
                            })
                            ->where('pr.plarutid', $request->codigo)->get();

                            //dd($tiquetes);

            $agencias  = DB::table('agencia as a')->select('a.agennombre',
                            DB::raw("CONCAT('OFI. ',a.agennombre,':') as nombreAgencia"),
                            DB::raw('(SELECT SUM(tiquvalorfondoreposicion) AS tiquvalorfondoreposicion FROM tiquete WHERE plarutid = pr.plarutid) AS totalFondoReposicion'))
                            ->join('planillaruta as pr', 'pr.agenid', '=', 'a.agenid')
                            ->where('pr.plarutid', $request->codigo)->get();

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
                            "usuarioDespacha"      => $planillaruta->usuarioDespacha,
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

    public function obtenerResumenPlanilla($codigo, $formatear = false)
    {
        $selects = [
            't.plarutid',
            DB::raw("SUM(t.tiquvalortotal) as valorTiquete"),
            DB::raw("SUM(t.tiquvalorfondorecaudo) as valorFondoRecaudo"),
            DB::raw("IFNULL(
                        (SELECT SUM(e.encovalorcomisionvehiculo) 
                        FROM encomienda e 
                        WHERE e.plarutid = t.plarutid), 
                        0
                    ) as valorEncomiendaVehiculo"),
            DB::raw("SUM(t.tiquvalortotal - t.tiquvalorfondorecaudo) + 
                    IFNULL(
                        (SELECT SUM(e.encovalorcomisionvehiculo) 
                        FROM encomienda e 
                        WHERE e.plarutid = t.plarutid), 
                        0
                    ) as valorEntregar")
        ];

        if ($formatear) {
            $selects = [
                't.plarutid',
                DB::raw("CONCAT('$ ', FORMAT(SUM(t.tiquvalortotal), 0)) as valorTiquete"),
                DB::raw("CONCAT('$ ', FORMAT(SUM(t.tiquvalorfondorecaudo), 0)) as valorFondoRecaudo"),
                DB::raw("CONCAT('$ ', FORMAT( IFNULL(
                                                    (SELECT SUM(e.encovalorcomisionvehiculo) 
                                                    FROM encomienda e 
                                                    WHERE e.plarutid = t.plarutid), 
                                                    0
                                                ), 0) ) as valorEncomiendaVehiculo"),
                DB::raw("CONCAT('$ ', FORMAT(
                                    SUM(t.tiquvalortotal - t.tiquvalorfondorecaudo) + 
                                        IFNULL(
                                            (SELECT SUM(e.encovalorcomisionvehiculo) 
                                            FROM encomienda e 
                                            WHERE e.plarutid = t.plarutid), 
                                            0
                                        ), 0)
                                ) as valorEntregar")
            ];
        }

        return DB::table('tiquete as t')
                ->select($selects)
                ->where('t.plarutid', $codigo)
                ->groupBy('t.plarutid')
                ->first();
    }

    public function obtenerConsecutivo($anioActual)
	{
        $consecutivoPlanilla = DB::table('planillaruta')->select('plarutconsecutivo as consecutivo')
                                                        ->where('plarutanio', $anioActual)
                                                        ->where('agenid', auth()->user()->agenid)
                                                        ->orderBy('plarutid', 'desc')->first();
        $consecutivo = ($consecutivoPlanilla === null) ? 1 : $consecutivoPlanilla->consecutivo + 1;
        return str_pad($consecutivo,  4, "0", STR_PAD_LEFT);
    }
}