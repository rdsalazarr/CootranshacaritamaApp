<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\PersonaServicioPuntosAcomulados;
use App\Models\Despacho\PersonaServicioFidelizacion;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Models\Despacho\PersonaServicio;
use App\Models\Despacho\TiquetePuesto;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use App\Models\Caja\CuentaContable;
use App\Models\Despacho\Tiquete;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon;

class TiqueteController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['estado' => 'required']);
        try{
            $rutaDespachada  = ($request->tipo === 'REGISTRADO') ? false : true;
            $fechaHoraActual = Carbon::now();
            $fechaInicial    = $fechaHoraActual->subMonths(6)->format('Y-m-d');

            $consulta   = DB::table('tiquete as t')
                            ->select('t.tiquid','pr.rutaid', 't.tiqufechahoraregistro as fechaHoraRegistro','pr.plarutfechahorasalida as fechaSalida',
                            'mo.muninombre as municipioOrigen', 'md.muninombre as municipioDestino','t.plarutid',
                            DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                            DB::raw("CONCAT(t.agenid, t.tiquanio, t.tiquconsecutivo) as numeroTiquete"),
                            DB::raw("CONCAT(ps.perserprimernombre,' ',IFNULL(ps.persersegundonombre,''),' ',ps.perserprimerapellido,' ',IFNULL(ps.persersegundoapellido,'')) as nombreCliente"))
                            ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
                            ->join('municipio as mo', function($join)
                            {
                                $join->on('mo.munidepaid', '=', 't.tiqudepaidorigen');
                                $join->on('mo.muniid', '=', 't.tiqumuniidorigen');
                            })
                            ->join('municipio as md', function($join)
                            {
                                $join->on('md.munidepaid', '=', 't.tiqudepaiddestino');
                                $join->on('md.muniid', '=', 't.tiqumuniiddestino');
                            })
                            ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                            ->join('vehiculo as v', 'v.vehiid', '=', 'pr.vehiid')
                            ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                            ->where(function ($query) {
                                $query->where('pr.agenid', auth()->user()->agenid)
                                      ->orWhere('t.usuaid', Auth::id());
                            })
                            ->where('pr.plarutdespachada', $rutaDespachada);

                            if($rutaDespachada)
                                $consulta = $consulta->whereDate('t.tiqufechahoraregistro', '>=', $fechaInicial);

                 $data  = $consulta->orderBy('t.tiquid', 'Desc')->orderBy('pr.plarutid', 'Desc')->get();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        try{
            $cajaAbierta          = MovimientoCaja::verificarCajaAbierta();
            $tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4', '5'])->orderBy('tipidenombre')->get();
            $municipios           = DB::table('municipio as m')->distinct()
                                        ->select('m.muniid', 'm.munidepaid', 'm.muninombre as muninombre', 'tt.rutaid', DB::raw("CONCAT('ORIGEN') as tipo"))
                                        ->join('tarifatiquete as tt', function ($join) {
                                            $join->on('tt.tartiqdepaidorigen', '=', 'm.munidepaid');
                                            $join->on('tt.tartiqmuniidorigen', '=', 'm.muniid');
                                        })
                                        ->where('m.munihacepresencia', true)
                                        ->unionAll(DB::table('municipio as m')->distinct()
                                            ->select('m.muniid', 'm.munidepaid', 'm.muninombre as muninombre', 'tt.rutaid', DB::raw("CONCAT('DESTINO') as tipo"))
                                            ->join('tarifatiquete as tt', function ($join) {
                                                $join->on('tt.tartiqdepaiddestino', '=', 'm.munidepaid');
                                                $join->on('tt.tartiqmuniiddestino', '=', 'm.muniid');
                                            })
                                            ->where('m.munihacepresencia', true)
                                        )
                                        ->orderBy('muninombre')
                                        ->get();  

            $tarifaTiquetes         = DB::table('tarifatiquete as tt')
                                        ->select('tt.rutaid','tt.tartiqdepaidorigen','tt.tartiqmuniidorigen','tt.tartiqdepaiddestino','tt.tartiqmuniiddestino', 
                                        'tt.tartiqvalor', 'tt.tartiqfondoreposicion', 'tt.tartiqvalorestampilla','tt.tartiqvalorseguro','tt.tartiqvalorfondorecaudo')
                                        ->join('ruta as r', 'r.rutaid', '=', 'tt.rutaid')
                                        ->join('planillaruta as pr', 'pr.rutaid', '=', 'tt.rutaid')
                                        ->where('r.rutaactiva', true)
                                        ->where('pr.plarutdespachada', false)
                                        ->get();

            $planillaRutas        = DB::table('planillaruta as pr')
                                        ->select('pr.rutaid','pr.vehiid','pr.plarutid','r.rutadepaidorigen','r.rutamuniidorigen','r.rutadepaiddestino','r.rutamuniiddestino',
                                        'mo.muninombre as municipioOrigen','md.muninombre as municipioDestino',
                                        DB::raw("CONCAT(pr.agenid,pr.plarutconsecutivo,' - ', mo.muninombre,' - ', md.muninombre, ' - ', pr.plarutfechahorasalida) as nombreRuta"))
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
                                        ->where('pr.plarutdespachada', false)
                                        ->get();

            $distribucionVehiculos = DB::table('tipovehiculodistribucion as tvd')
                                        ->select('tvd.tivediid','tvd.tipvehid','tvd.tivedicolumna', 'tvd.tivedifila', 'tvd.tivedipuesto','tv.tipvehclasecss','v.vehiid',
                                        DB::raw('(SELECT COUNT(DISTINCT(tvd1.tivedifila)) FROM tipovehiculodistribucion as tvd1 WHERE tvd1.tipvehid = tvd.tipvehid) AS totalFilas'))
                                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'tvd.tipvehid')
                                        ->join('vehiculo as v', 'v.tipvehid', '=', 'tv.tipvehid')
                                        ->orderBy('tvd.tivediid')
                                        ->get();

            $tiquete                = [];
            $tiquetePuestos         = [];
            $tiquetePuestosPlanilla = [];
            if($request->tipo === 'U'){
                $tiquete  = DB::table('tiquete as t')
                                    ->select('t.tiquid','t.plarutid','t.perserid','t.tiqudepaidorigen','t.tiqumuniidorigen','t.tiqudepaiddestino','t.tiqumuniiddestino',
                                    't.tiquvalortiquete','t.tiquvalordescuento', 't.tiquvalorfondorecaudo','t.tiquvalorseguro','t.tiquvalorestampilla',
                                    't.tiquvalorfondoreposicion','t.tiquvalortotal','t.tiqucantidad', DB::raw("if(t.tiqucontabilizado = 1 ,'SI', 'NO') as contabilizado"),
                                    'ps.tipideid','ps.perserdocumento','ps.perserprimernombre','ps.persersegundonombre','ps.perserprimerapellido','ps.perserpermitenotificacion',
                                    'ps.persersegundoapellido','ps.perserdireccion', 'ps.persercorreoelectronico','ps.persernumerocelular', 'pr.vehiid','pr.rutaid')
                                    ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                                    ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
                                    ->where('t.tiquid', $request->codigo)->first();

                $tiquetePuestos  = DB::table('tiquetepuesto')->select('tiqpueid','tiqpuenumeropuesto')
                                    ->where('tiquid', $request->codigo)->get();

                $tiquetePuestosPlanilla  = DB::table('tiquetepuesto as tp')->select('tp.tiqpuenumeropuesto')
                                        ->join('tiquete as t', 't.tiquid', '=', 'tp.tiquid')
                                        ->where('t.plarutid', $request->planillaId)
                                        ->where('t.tiquid', '<>', $request->codigo)->get();
            }

            return response()->json([ "tipoIdentificaciones" => $tipoIdentificaciones, "planillaRutas"          => $planillaRutas,         "tarifaTiquetes" => $tarifaTiquetes,
                                    "municipios"             => $municipios,           "distribucionVehiculos"  => $distribucionVehiculos, "tiquete"        => $tiquete,  
                                    "tiquetePuestos"         => $tiquetePuestos,       "tiquetePuestosPlanilla" => $tiquetePuestosPlanilla, "cajaAbierta"   => $cajaAbierta]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function consultarVenta(Request $request)
	{
        $this->validate(request(),['planillaId' => 'required|numeric']);

        try{
            $data  = DB::table('tiquete as t')
                        ->select('tp.tiqpueid','tp.tiqpuenumeropuesto')
                        ->join('tiquetepuesto as tp', 'tp.tiquid', '=', 't.tiquid')
                        ->where('t.plarutid', $request->planillaId)->get();

            return response()->json(['success' => (count($data) > 0) ? true : false, 'data' => $data]);
          }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'documento' => 'required|string|max:15']);

        try{
            $data   = DB::table('personaservicio')
                                ->select('perserid','tipideid','perserdocumento','perserprimernombre','persersegundonombre','perserprimerapellido',
                                        'persersegundoapellido','perserdireccion', 'persercorreoelectronico','persernumerocelular','perserpermitenotificacion',
                                        DB::raw('(SELECT SUM(pspa.pesepavalorredimido) FROM personaserpuntosacomulados as pspa WHERE pesepapagado = "0" AND pspa.perserid = perserid ) AS totalPuntosAcomulados'))
                                ->where('tipideid', $request->tipoIdentificacion)
                                ->where('perserdocumento', $request->documento)->first();

            return response()->json(['success' => ($data !== null) ? true : false, 'data' => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salve(Request $request)
	{
        $tiquid          = $request->codigo;
		$personaId       = $request->personaId; 
        $tiquete         = ($tiquid != 000) ? Tiquete::findOrFail($tiquid) : new Tiquete();
        $personaservicio = ($personaId != 000) ? PersonaServicio::findOrFail($personaId) : new PersonaServicio();

  	    $this->validate(request(),[
			    'tipoIdentificacion'     => 'required|numeric',
				'documento'              => 'required|string|min:6|max:15|unique:personaservicio,perserdocumento,'.$personaservicio->perserid.',perserid',
				'primerNombre'           => 'required|string|min:3|max:140',
				'segundoNombre'          => 'nullable|string|min:3|max:40',
				'primerApellido'         => 'nullable|string|min:4|max:40',
				'segundoApellido'        => 'nullable|string|min:4|max:40',
				'direccion'              => 'required|string|min:4|max:100',
				'correo'                 => 'nullable|email|string|max:80',
				'telefonoCelular'        => 'nullable|string|max:20',
                'departamentoOrigen'     => 'required|numeric',
                'municipioOrigen'        => 'required|numeric',
                'departamentoDestino'    => 'required|numeric',
                'municipioDestino'       => 'required|numeric',
                'planillaId'             => 'required|numeric',
                'valorTiquete'           => 'required|numeric|between:1,99999999',
                'valorDescuento'         => 'nullable|numeric|between:1,99999999',
                'valorFondoReposicion'   => 'required|numeric|between:1,99999999',
                'valorTotal'             => 'required|numeric|between:1,99999999',
                'valorTotalSeguro'       => 'nullable|numeric|between:0,99999999',
                'valorFondoRecaudoTotal' => 'required|numeric|between:1,99999',
                'valorTotalEstampilla'   => 'required|numeric',
                'puestosVendidos'        => 'required|array|min:1', 
	        ]);

        DB::beginTransaction();
        try {
            $valorContabilizar                          = $request->valorTotal;
            $valorFondoReposicion                       = $request->valorFondoReposicion;
            $valorEstampilla                            = $request->valorTotalEstampilla;
            $valorSeguro                                = $request->valorTotalSeguro;
            $valorFondoRecaudo                          = $request->valorFondoRecaudoTotal;
            $valorPuntosRedimidos                       = ($request->redimirPuntos === 'SI') ? $request->totalPuntosAcomulados : 0;
            $valorTiqueteContabilizar                   = $valorContabilizar - $valorFondoReposicion - $valorEstampilla -  $valorSeguro - $valorFondoRecaudo;
            $nombreCliente                              = $request->primerNombre.' '.$request->segundoNombre.' '.$request->primerApellido.' '.$request->segundoApellido;
            $fechaHoraActual                            = Carbon::now();
            $generales                                  = new generales();

            //Registra o actualiza la persona del servicio
			$personaservicio->tipideid                  = $request->tipoIdentificacion;
			$personaservicio->perserdocumento           = $request->documento;
			$personaservicio->perserprimernombre        = mb_strtoupper($request->primerNombre,'UTF-8');
			$personaservicio->persersegundonombre       = mb_strtoupper($request->segundoNombre,'UTF-8');
			$personaservicio->perserprimerapellido      = mb_strtoupper($request->primerApellido,'UTF-8');
			$personaservicio->persersegundoapellido     = mb_strtoupper($request->segundoApellido,'UTF-8');
			$personaservicio->perserdireccion           = $request->direccion;
			$personaservicio->persercorreoelectronico   = $request->correo;
			$personaservicio->persernumerocelular       = $request->telefonoCelular;
            $personaservicio->perserpermitenotificacion = ($request->enviarTiquete === 'SI') ? 1 : 0;
			$personaservicio->save();

            if($request->tipo === 'I' and $personaId === '000'){
				//Consulto el ultimo identificador de la persona de la tiquete
				$personaservicioConsecutivo = PersonaServicio::latest('perserid')->first();
				$personaId                  = $personaservicioConsecutivo->perserid;
			}

            if($request->tipo === 'I'){
                $anioActual                     = $fechaHoraActual->year;
                $tiquete->agenid                = auth()->user()->agenid;
                $tiquete->usuaid                = Auth::id();
                $tiquete->tiqufechahoraregistro = $fechaHoraActual;
                $tiquete->tiquanio              = $anioActual;
                $tiquete->tiquconsecutivo       = Tiquete::obtenerConsecutivo($anioActual);
            }

            $tiquete->plarutid                 = $request->planillaId;
            $tiquete->perserid                 = $personaId;
			$tiquete->tiqudepaidorigen         = $request->departamentoOrigen;
			$tiquete->tiqumuniidorigen         = $request->municipioOrigen;
			$tiquete->tiqudepaiddestino        = $request->departamentoDestino;
            $tiquete->tiqumuniiddestino        = $request->municipioDestino;
            $tiquete->tiqucantidad             = $request->cantidadPuesto;
            $tiquete->tiquvalortiquete         = $request->valorTiquete;
            $tiquete->tiquvalordescuento       = $request->valorDescuento;
            $tiquete->tiquvalorseguro          = $valorSeguro;
            $tiquete->tiquvalorestampilla      = $valorEstampilla;
            $tiquete->tiquvalorfondoreposicion = $valorFondoReposicion;
            $tiquete->tiquvalorfondorecaudo    = $valorFondoRecaudo;
            $tiquete->tiquvalorpuntosredimido  = $valorPuntosRedimidos;
            $tiquete->tiquvalortotal           = $valorContabilizar - $valorPuntosRedimidos ;//- $valorFondoRecaudo
            $tiquete->tiqucontabilizado        = ($valorPuntosRedimidos > 0 ) ? 1 : 0; 
			$tiquete->save();

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador de la tiquete
				$tiqueteConsecutivo      = Tiquete::latest('tiquid')->first();
				$tiquid                  = $tiqueteConsecutivo->tiquid;
                $fidelizacioncliente     = DB::table('fidelizacioncliente')->select('fidclivalorfidelizacion')->where('fidcliid', 1)->first();
                $valorTotal              = $valorContabilizar - $valorPuntosRedimidos;
                $totalPuntosFidelizacion = intval($valorTotal / $fidelizacioncliente->fidclivalorfidelizacion);

                if($totalPuntosFidelizacion > 0){
                    $personaserviciofidelizacion                          = new PersonaServicioFidelizacion();
                    $personaserviciofidelizacion->agenid                  = auth()->user()->agenid;
                    $personaserviciofidelizacion->usuaid                  = Auth::id();
                    $personaserviciofidelizacion->perserid                = $personaId;
                    $personaserviciofidelizacion->pesefifechahoraregistro = $fechaHoraActual;
                    $personaserviciofidelizacion->pesefitipoproceso       = 'E';
                    $personaserviciofidelizacion->pesefinumeropunto       = $totalPuntosFidelizacion;
                    $personaserviciofidelizacion->save();
                }

                if($request->redimirPuntos === 'SI'){
                    $cajaAbierta          = MovimientoCaja::verificarCajaAbierta();
                    if(!$cajaAbierta){
                        return response()->json(['success' => false, 'message' => 'Para poder redimir puntos, es necesario tener una caja abierta. Sin la apertura de la caja, no es posible realizar la redención de puntos']);
                    }

                    //Marco los puntos como gastados
                    $personasSerPuntosAcomulados = DB::table('personaserpuntosacomulados')->select('pesepaid')->where('perserid', $personaId)->get();
                    foreach($personasSerPuntosAcomulados as $personaSerPuntoAcomulado){
                        $personaserpuntosacomulados                        = PersonaServicioPuntosAcomulados::findOrFail($personaSerPuntoAcomulado->pesepaid);
                        $personaserpuntosacomulados->usuaid                = Auth::id();
                        $personaserpuntosacomulados->pesepafechahorapagado = $fechaHoraActual;
                        $personaserpuntosacomulados->pesepapagado          = true;
                        $personaserpuntosacomulados->save();
                    }

                    //Se realiza la contabilizacion
                    $fechaActual                                 = $fechaHoraActual->format('Y-m-d');
                    $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
                    $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                    $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                    $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('caja');
                    $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                    $comprobantecontabledetalle->cocodemonto     = $valorContabilizar - $valorPuntosRedimidos;
                    $comprobantecontabledetalle->save();

                    $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                    $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                    $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('pagoTiquete');
                    $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                    $comprobantecontabledetalle->cocodemonto     = $generales->redondearCienMasCercano($valorTiqueteContabilizar);
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

                    if($valorPuntosRedimidos  > 0){
                        $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                        $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                        $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('valorPuntoRedimir');
                        $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                        $comprobantecontabledetalle->cocodemonto     = $valorPuntosRedimidos;
                        $comprobantecontabledetalle->save();
                    }
                }

			}else{
                $tiquetePuestos  = DB::table('tiquetepuesto as tp')->select('tp.tiqpueid')
                                    ->join('tiquete as t', 't.tiquid', '=', 'tp.tiquid')
                                    ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                                    ->where('tp.tiquid', $tiquid)
                                    ->where('ps.perserid', $personaId)->get();
                foreach($tiquetePuestos as $tiquetePuesto){
                    $tiquetepuesto = TiquetePuesto::findOrFail($tiquetePuesto->tiqpueid);
                    $tiquetepuesto->delete();
                }
            }

            foreach ($request->puestosVendidos as $puestoVendido)
            {
                $tiquetepuesto 		               = new TiquetePuesto();
                $tiquetepuesto->tiquid             = $tiquid;
                $tiquetepuesto->tiqpuenumeropuesto = $puestoVendido['tivedipuesto'];
                $tiquetepuesto->save();
            }

            if($request->enviarTiquete === 'SI' && $request->correo !== ''){//Notifico al correo
                $arrayPdf   = [];
			    array_push($arrayPdf, $this->generarFacturaPdf($tiquid, 'F')); 
                $empresa            = DB::table('empresa')->select('emprnombre','emprsigla','emprcorreo')->where('emprid', 1)->first();
                $notificar          = new notificar();
                $informacioncorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificacionConfirmacionTiquete')->first(); 
                $email              = $request->correo;
                $nombreCliente      = mb_strtoupper($nombreCliente,'UTF-8');
                $buscar             = Array('nombreCliente');
                $remplazo           = Array($nombreCliente); 
                $asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
                $msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
                $enviarcopia        = $informacioncorreo->innocoenviarcopia;
                $enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
                $notificar->correo([$email], $asunto, $msg, [$arrayPdf], $empresa->emprcorreo, $enviarcopia, $enviarpiepagina);
            }

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', 'tiqueteId' => $tiquid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function show(Request $request)
    {
		$this->validate(request(),['codigo'  => 'required']);

        try{
            $tiquete  = DB::table('tiquete as t')
                        ->select('t.tiquid', 't.tiquvalortiquete','t.tiquvalordescuento', 't.tiquvalorfondoreposicion','t.tiquvalortotal','t.tiqucantidad','t.tiquvalorseguro','t.tiquvalorestampilla',
                        DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo,' - ', mo.muninombre,' - ', md.muninombre) as nombreRuta"), 't.plarutid', 'pr.vehiid','tt.tartiqvalorseguro',
                        'dd.depanombre as deptoDestino', 'mde.muninombre as municipioDestino', 'ps.tipideid','ps.perserdocumento','ps.perserprimernombre','ps.persersegundonombre','ps.perserprimerapellido',
                        'ps.persersegundoapellido','ps.perserdireccion', 'ps.persercorreoelectronico','ps.persernumerocelular', 'ti.tipidenombre as tipoIdentificacion',
                        't.tiqudepaidorigen', 't.tiqumuniidorigen',  't.tiqudepaiddestino','t.tiqumuniiddestino')
                        ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                        ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'ps.tipideid')
                        ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
                        ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                        ->join('tarifatiquete as tt', function($join)
                        {
                            $join->on('tt.rutaid', '=', 'pr.rutaid');
                            $join->on('tt.tartiqdepaidorigen', '=', 't.tiqudepaidorigen');
                            $join->on('tt.tartiqmuniidorigen', '=', 't.tiqumuniidorigen');
                        })   
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
                        ->join('departamento as dd', 'dd.depaid', '=', 't.tiqudepaiddestino')
                        ->join('municipio as mde', function($join)
                        {
                            $join->on('mde.munidepaid', '=', 't.tiqudepaiddestino');
                            $join->on('mde.muniid', '=', 't.tiqumuniiddestino');
                        })
                        ->where('t.tiquid', $request->codigo)->first();

            $tiquetePuestos  = DB::table('tiquetepuesto')->select('tiqpueid','tiqpuenumeropuesto')
                                        ->where('tiquid', $request->codigo)->get();

            $distribucionVehiculo = DB::table('tipovehiculodistribucion as tvd')
                                        ->select('tvd.tivediid','tvd.tipvehid','tvd.tivedicolumna', 'tvd.tivedifila', 'tvd.tivedipuesto','tv.tipvehclasecss','v.vehiid',
                                        DB::raw('(SELECT COUNT(DISTINCT(tvd1.tivedifila)) FROM tipovehiculodistribucion as tvd1 WHERE tvd1.tipvehid = tvd.tipvehid) AS totalFilas'))
                                        ->join('vehiculo as v', 'v.tipvehid', '=', 'tvd.tipvehid')
                                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                        ->where('v.vehiid', $tiquete->vehiid)->get();

            return response()->json(['success' => true, "tiquete" => $tiquete, "tiquetePuestos" => $tiquetePuestos, "distribucionVehiculo" => $distribucionVehiculo ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function verFactura(Request $request)
    {
		$this->validate(request(),['codigo' => 'required']);
		try{
  			return response()->json(["data" => $this->generarFacturaPdf($request->codigo, 'S') ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }
	}

    public function generarFacturaPdf($tiquid, $metodo = 'S'){
        $tiquete  = DB::table('tiquete as t')
                            ->select('t.tiqufechahoraregistro', DB::raw("CONCAT(t.agenid,t.tiquanio,'',t.tiquconsecutivo) as numeroTiquete"),
                            't.tiquvalortiquete', 't.tiquvalordescuento','t.tiquvalortotal','t.tiquvalorseguro','t.tiquvalorestampilla',
                            DB::raw("CONCAT(mo.muninombre,' - ', md.muninombre) as nombreRuta"),
                           'mor.muninombre as municipioOrigen',  'mde.muninombre as municipioDestino',
                            DB::raw("CONCAT(ps.perserprimernombre,' ',if(ps.persersegundonombre is null ,'', ps.persersegundonombre),' ',
                            ps.perserprimerapellido,' ',if(ps.persersegundoapellido is null ,' ', ps.persersegundoapellido)) as nombreCliente"),
                            'ps.perserdireccion', 'ps.persernumerocelular',
                            DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"), 'a.agennombre', 'a.agendireccion',
                            DB::raw("CONCAT(a.agentelefonocelular, if(a.agentelefonofijo is null ,'', ' - '), a.agentelefonofijo) as telefonoAgencia"),
                            DB::raw("(SELECT menimpvalor FROM mensajeimpresion WHERE menimpnombre = 'TIQUETES') AS mensajeTiquete"))
                            ->join('personaservicio as ps', 'ps.perserid', '=', 't.perserid')
                            ->join('planillaruta as pr', 'pr.plarutid', '=', 't.plarutid')
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
                            ->join('municipio as mor', function($join)
                            {
                                $join->on('mor.munidepaid', '=', 't.tiqudepaidorigen');
                                $join->on('mor.muniid', '=', 't.tiqumuniidorigen');
                            })
                            ->join('municipio as mde', function($join)
                            {
                                $join->on('mde.munidepaid', '=', 't.tiqudepaiddestino');
                                $join->on('mde.muniid', '=', 't.tiqumuniiddestino');
                            })
                            ->join('usuario as u', 'u.usuaid', '=', 't.usuaid')
                            ->join('agencia as a', 'a.agenid', '=', 't.agenid')
                            ->where('t.tiquid', $tiquid)->first();

        $tiquetPuestos = DB::table('tiquetepuesto')->select('tiqpuenumeropuesto')->where('tiquid', $tiquid)->get();
        $numeroPuestos  = '';
        foreach($tiquetPuestos as $tiquetPuesto){
            $numeroPuestos  .= $tiquetPuesto->tiqpuenumeropuesto.', ';
        }

        $arrayDatos   = [
                            "numeroTiquete"     => $tiquete->numeroTiquete,
                            "fechaTiquete"      => $tiquete->tiqufechahoraregistro,
                            "rutaTiquete"       => $tiquete->nombreRuta,
                            "origenTiquete"     => $tiquete->municipioOrigen,
                            "destinoTiquete"    => $tiquete->municipioDestino,
                            "valorTiquete"      => number_format($tiquete->tiquvalortiquete, 0,',','.'),
                            "descuentoTiquete"  => number_format($tiquete->tiquvalordescuento, 0,',','.'),
                            "valorTotalTiquete" => number_format($tiquete->tiquvalortotal, 0,',','.'),
                            "valorSeguro"       => number_format($tiquete->tiquvalorseguro, 0,',','.'),
                            "valorEstampilla"   => number_format($tiquete->tiquvalorestampilla, 0,',','.'),
                            "numeroPuestos"     => substr($numeroPuestos, 0, -2),
                            "nombreCliente"     => $tiquete->nombreCliente,
                            "direccionCliente"  => $tiquete->perserdireccion,
                            "telefonoCliente"   => $tiquete->persernumerocelular,
                            "usuarioElabora"    => $tiquete->nombreUsuario,
                            "nombreAgencia"     => $tiquete->agennombre,
                            "direccionAgencia"  => $tiquete->agendireccion,
                            "telefonoAgencia"   => $tiquete->telefonoAgencia,
                            "mensajePlanilla"   => $tiquete->mensajeTiquete,
                            "metodo"            => $metodo
                        ];

        $generarPdf   = new generarPdf();

        return $generarPdf->facturaTiquete($arrayDatos);
    }
}