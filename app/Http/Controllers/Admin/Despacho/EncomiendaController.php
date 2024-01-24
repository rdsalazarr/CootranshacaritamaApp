<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\EncomiendaCambioEstado;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Models\Despacho\PersonaServicio;
use App\Http\Controllers\Controller;
use App\Models\Despacho\Encomienda;
use App\Models\Caja\MovimientoCaja;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use App\Util\generarPdf;
use App\Util\notificar;
use Carbon\Carbon;

class EncomiendaController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['estado' => 'required', 'tipo' => 'required']);

        $comparador      = ($request->tipo === 'REGISTRADO') ? '=' : '<>';
        $rutaDespachada  = ($request->tipo === 'REGISTRADO') ? false : true;
        $fechaHoraActual = Carbon::now();
        $fechaInicial    = $fechaHoraActual->subMonths(6)->format('Y-m-d');

        $consulta = DB::table('encomienda as e')->select('e.encoid','e.encofechahoraregistro as fechaHoraRegistro', 'te.tipencnombre as tipoEncomienda','tee.tiesennombre as estado',
                        DB::raw("CONCAT(de.depanombre,' - ',md.muninombre) as destinoEncomienda"),
                        DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo,' - ', mor.muninombre,' - ', mdr.muninombre) as nombreRuta"),
                        DB::raw("CONCAT(ps.perserprimernombre,' ',if(ps.persersegundonombre is null ,'', ps.persersegundonombre),' ',
                            ps.perserprimerapellido,' ',if(ps.persersegundoapellido is null ,' ', ps.persersegundoapellido)) as nombrePersonaRemitente"),
                        DB::raw("CONCAT(ps1.perserprimernombre,' ',if(ps1.persersegundonombre is null ,'', ps1.persersegundonombre),' ',
                                ps1.perserprimerapellido,' ',if(ps1.persersegundoapellido is null ,' ', ps1.persersegundoapellido)) as nombrePersonaDestino"))
                        ->join('personaservicio as ps', 'ps.perserid', '=', 'e.perseridremitente')
                        ->join('personaservicio as ps1', 'ps1.perserid', '=', 'e.perseriddestino')
                        ->join('tipoencomienda as te', 'te.tipencid', '=', 'e.tipencid')
                        ->join('tipoestadoencomienda as tee', 'tee.tiesenid', '=', 'e.tiesenid')
                        ->join('departamento as de', 'de.depaid', '=', 'e.depaiddestino')
                        ->join('municipio as md', function($join)
                        {
                            $join->on('md.munidepaid', '=', 'e.depaiddestino');
                            $join->on('md.muniid', '=', 'e.muniiddestino');
                        })
                        ->join('planillaruta as pr', 'pr.plarutid', '=', 'e.plarutid')
                        ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                        ->join('municipio as mor', function($join)
                        {
                            $join->on('mor.munidepaid', '=', 'r.depaidorigen');
                            $join->on('mor.muniid', '=', 'r.muniidorigen');
                        })
                        ->join('municipio as mdr', function($join)
                        {
                            $join->on('mdr.munidepaid', '=', 'r.depaiddestino');
                            $join->on('mdr.muniid', '=', 'r.muniiddestino');
                        })
                        ->where('e.tiesenid', $comparador, $request->estado)
                        ->where('pr.plarutdespachada', $rutaDespachada);

                        if($rutaDespachada)
                            $consulta = $consulta->whereDate('e.encofechahoraregistro', '>=', $fechaInicial);
                    
                    $data  = $consulta->orderBy('e.encoid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        $tiposEncomiendas        = DB::table('tipoencomienda')->select('tipencid','tipencnombre')->orderBy('tipencnombre')->get();
        $municipios              = DB::table('municipio as m')
                                        ->select('m.muniid','m.munidepaid','m.muninombre')
                                        ->join('rutanodo as rn', 'rn.muniid', '=', 'm.muniid')
                                        ->where('m.munihacepresencia', true)->orderBy('m.muninombre')->get();

        $tipoIdentificaciones    = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4', '5'])->orderBy('tipidenombre')->get();
        $configuracionEncomienda = DB::table('configuracionencomienda')
                                            ->select('conencvalorminimoenvio','conencvalorminimodeclarado','conencporcentajeseguro','conencporcencomisionempresa',
                                            'conencporcencomisionagencia', 'conencporcencomisionvehiculo')->where('conencid', 1)->first();

        $planillaRutas        = DB::table('planillaruta as pr')
                                    ->select('pr.plarutid','r.depaidorigen','r.muniidorigen','r.depaiddestino','r.muniiddestino','mo.muninombre as municipioOrigen','md.muninombre as municipioDestino',
                                    DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo,' - ', mo.muninombre,' - ', md.muninombre, ' - ', pr.plarutfechahorasalida) as nombreRuta"))
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
                                    ->where('pr.plarutdespachada', false)
                                    ->get();

        $encomienda            = [];
        $cajaAbierta           = false;
        $mensajeCaja           = 'Lo sentimos, no es posible registrar una encomienda sin antes haber abierto la caja para el día de hoy';
        $municipiosNodoDestino = [];
        if($request->tipo === 'U'){
            $encomienda  = DB::table('encomienda as e')
                                ->select('e.encoid','e.plarutid','e.perseridremitente','e.perseriddestino','e.depaidorigen','e.muniidorigen','e.depaiddestino','e.muniiddestino','e.tipencid',
                                'e.encocontenido','e.encocantidad','e.encovalorcomisionseguro','e.encovalordeclarado','e.encovalorenvio','e.encovalortotal','e.encovalordomicilio', 'e.encoobservacion',
                                'e.encopagocontraentrega','e.encocontabilizada','psr.tipideid','psr.perserdocumento','psr.perserprimernombre','psr.persersegundonombre','psr.perserprimerapellido',
                                'psr.persersegundoapellido','psr.perserdireccion', 'psr.persercorreoelectronico','psr.persernumerocelular','psr.perserpermitenotificacion',
                                'psd.tipideid as tipideidDestino','psd.perserdocumento as perserdocumentoDestino','psd.perserprimernombre as perserprimernombreDestino',
                                'psd.persersegundonombre as persersegundonombreDestino','psd.perserprimerapellido as perserprimerapellidoDestino',
                                'psd.persersegundoapellido as persersegundoapellidoDestino','psd.perserdireccion as perserdireccionDestino', 
                                'psd.persercorreoelectronico as persercorreoelectronicoDestino', 'psd.persernumerocelular as persernumerocelularDestino')
                                ->join('personaservicio as psr', 'psr.perserid', '=', 'e.perseridremitente')
                                ->join('personaservicio as psd', 'psd.perserid', '=', 'e.perseriddestino')
                                ->where('e.encoid', $request->codigo)->first();

            $municipiosNodoDestino  = DB::table('municipio as m')
                                        ->select('m.muniid','m.munidepaid','m.muninombre')
                                        ->join('rutanodo as rn', 'rn.muniid', '=', 'm.muniid')
                                        ->join('planillaruta as pr', 'pr.rutaid', '=', 'rn.rutaid')
                                        ->where('m.munihacepresencia', true)
                                        ->where('pr.plarutid', $encomienda->plarutid)
                                        ->orderBy('m.muninombre')->get();
        }else{
            $cajaAbierta = MovimientoCaja::verificarCajaAbierta();
            $mensajeCaja = '';
        }

        return response()->json(["tiposEncomiendas"       => $tiposEncomiendas,        "tipoIdentificaciones" => $tipoIdentificaciones, "planillaRutas"         => $planillaRutas,
                                "configuracionEncomienda" => $configuracionEncomienda, "municipios"           => $municipios,           "encomienda"            => $encomienda,
                                "cajaAbierta"             => $cajaAbierta,             "mensajeCaja"          => $mensajeCaja,          "municipiosNodoDestino" => $municipiosNodoDestino]);
    }

    public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'documento' => 'required|string|max:15']);

        $data   = DB::table('personaservicio')
                            ->select('perserid','tipideid','perserdocumento','perserprimernombre','persersegundonombre','perserprimerapellido',
                            			'persersegundoapellido','perserdireccion', 'persercorreoelectronico','persernumerocelular','perserpermitenotificacion')
                            ->where('tipideid', $request->tipoIdentificacion)
                            ->where('perserdocumento', $request->documento)->first();

        return response()->json(['success' => ($data !== null) ? true : false, 'data' => $data]);
    }

    public function salve(Request $request)
	{
        $encoid                   = $request->codigo;
		$personaIdRemitente       = $request->personaIdRemitente;
        $personaIdDestino         = $request->personaIdDestino;
        $encomienda               = ($encoid != 000) ? Encomienda::findOrFail($encoid) : new Encomienda();
        $personaservicioRemitente = ($personaIdRemitente != 000) ? PersonaServicio::findOrFail($personaIdRemitente) : new PersonaServicio();
        $personaservicioDestino   = ($personaIdDestino != 000) ? PersonaServicio::findOrFail($personaIdDestino) : new PersonaServicio();

	    $this->validate(request(),[
			    'tipoIdentificacionRemitente' => 'required|numeric',
				'documentoRemitente'          => 'required|string|min:6|max:15|unique:personaservicio,perserdocumento,'.$personaservicioRemitente->perserid.',perserid',
				'primerNombreRemitente'       => 'required|string|min:3|max:140',
				'segundoNombreRemitente'      => 'nullable|string|min:3|max:40',
				'primerApellidoRemitente'     => 'nullable|string|min:4|max:40',
				'segundoApellidoRemitente'    => 'nullable|string|min:4|max:40',
				'direccionRemitente'          => 'required|string|min:4|max:100',
				'correoRemitente'             => 'nullable|email|string|max:80',
				'telefonoCelularRemitente'    => 'nullable|string|max:20',
                'tipoIdentificacionDestino'   => 'required|numeric',
                'documentoDestino'            => 'required|string|min:6|max:15',
				'primerNombreDestino'         => 'required|string|min:3|max:140',
				'segundoNombreDestino'        => 'nullable|string|min:3|max:40',
				'primerApellidoDestino'       => 'nullable|string|min:4|max:40',
				'segundoApellidoDestino'      => 'nullable|string|min:4|max:40',
				'direccionDestino'            => 'required|string|min:4|max:100',
				'correoDestino'               => 'nullable|email|string|max:80',
				'telefonoCelularDestino'      => 'nullable|string|max:20',
                'departamentoOrigen'          => 'required|numeric',
                'municipioOrigen'             => 'required|numeric',
                'departamentoDestino'         => 'required|numeric',
                'municipioDestino'            => 'required|numeric',
                'ruta'                        => 'required|numeric',
				'tipoEncomienda'              => 'required|string',
				'cantidad'                    => 'required|numeric|between:1,999',
                'valorDeclarado'              => 'required|numeric|between:1,99999999',
                'valorEnvio'                  => 'required|numeric|between:1,99999999',
                'valorDomicilio'              => 'nullable|numeric|between:1,99999999',
                'contenido'                   => 'required|string|max:1000',
				'observaciones'               => 'nullable|string|max:500'
	        ]);

        DB::beginTransaction();
        try {

            //Consulto los valores de la encomienda
            $configuracionencomienda    = DB::table('configuracionencomienda')
                                            ->select('conencvalorminimoenvio','conencporcentajeseguro','conencporcencomisionempresa',
                                            'conencporcencomisionagencia', 'conencporcencomisionvehiculo')->where('conencid', 1)->first();

            $porcentajeSeguro           = $configuracionencomienda->conencporcentajeseguro;
            $porcentajeComisionEmpresa  = $configuracionencomienda->conencporcencomisionempresa;
            $porcentajeComisionAgencia  = $configuracionencomienda->conencporcencomisionagencia;
            $porcentajeComisionVehiculo = $configuracionencomienda->conencporcencomisionvehiculo;
            $estadoEncomienda           = 'R';
            $fechaHoraActual            = Carbon::now();
            $fechaActual                = $fechaHoraActual->format('Y-m-d');
            $valorTotalEncomienda       = intval($request->valorSeguro) + intval($request->valorEnvio) + intval($request->valorDomicilio);
            $nombreCliente              = $request->primerNombreRemitente.' '.$request->segundoNombreRemitente.' '.$request->primerApellidoRemitente.' '.$request->segundoApellidoRemitente;
			$personaservicioRemitente->tipideid                  = $request->tipoIdentificacionRemitente;
			$personaservicioRemitente->perserdocumento           = $request->documentoRemitente;
			$personaservicioRemitente->perserprimernombre        = mb_strtoupper($request->primerNombreRemitente,'UTF-8');
			$personaservicioRemitente->persersegundonombre       = mb_strtoupper($request->segundoNombreRemitente,'UTF-8');
			$personaservicioRemitente->perserprimerapellido      = mb_strtoupper($request->primerApellidoRemitente,'UTF-8');
			$personaservicioRemitente->persersegundoapellido     = mb_strtoupper($request->segundoApellidoRemitente,'UTF-8');
			$personaservicioRemitente->perserdireccion           = $request->direccionRemitente;
			$personaservicioRemitente->persercorreoelectronico   = $request->correoRemitente;
			$personaservicioRemitente->persernumerocelular       = $request->telefonoCelularRemitente;
            $personaservicioRemitente->perserpermitenotificacion = ($request->enviarEncomienda === 'SI') ? 1 : 0;
			$personaservicioRemitente->save();

            if($request->tipo === 'I' and $personaIdRemitente === '000'){
				//Consulto el ultimo identificador de la persona de la encomienda
				$personaservicioConsecutivo = PersonaServicio::latest('perserid')->first();
				$personaIdRemitente         = $personaservicioConsecutivo->perserid;
			}

            $personaservicioDestino->tipideid                = $request->tipoIdentificacionDestino;
			$personaservicioDestino->perserdocumento         = $request->documentoDestino;
			$personaservicioDestino->perserprimernombre      = mb_strtoupper($request->primerNombreDestino,'UTF-8');
			$personaservicioDestino->persersegundonombre     = mb_strtoupper($request->segundoNombreDestino,'UTF-8');
			$personaservicioDestino->perserprimerapellido    = mb_strtoupper($request->primerApellidoDestino,'UTF-8');
			$personaservicioDestino->persersegundoapellido   = mb_strtoupper($request->segundoApellidoDestino,'UTF-8');
			$personaservicioDestino->perserdireccion         = $request->direccionDestino;
			$personaservicioDestino->persercorreoelectronico = $request->correoDestino;
			$personaservicioDestino->persernumerocelular     = $request->telefonoCelularDestino;
			$personaservicioDestino->save();

            if($request->tipo === 'I' and $personaIdDestino === '000'){
				//Consulto el ultimo identificador de la persona de la encomienda
				$personaservicioConsecutivo = PersonaServicio::latest('perserid')->first();
				$personaIdDestino           = $personaservicioConsecutivo->perserid;
			}

            if($request->tipo === 'I'){
                $anioActual                         = $fechaHoraActual->year;
                $encomienda->agenid                 = auth()->user()->agenid;
                $encomienda->tiesenid               = $estadoEncomienda;
                $encomienda->usuaid                 = Auth::id();
                $encomienda->encofechahoraregistro  = $fechaHoraActual;
                $encomienda->encoanio               = $anioActual;
                $encomienda->encoconsecutivo        = $this->obtenerConsecutivo($anioActual);
            }

			$encomienda->perseridremitente         = $personaIdRemitente;
			$encomienda->perseriddestino           = $personaIdDestino; 
            $encomienda->plarutid                  = $request->ruta;
			$encomienda->depaidorigen              = $request->departamentoOrigen;
			$encomienda->muniidorigen              = $request->municipioOrigen;
			$encomienda->depaiddestino             = $request->departamentoDestino;
            $encomienda->muniiddestino             = $request->municipioDestino;
			$encomienda->tipencid                  = $request->tipoEncomienda;
            $encomienda->encocantidad              = $request->cantidad;
            $encomienda->encovalordeclarado        = $request->valorDeclarado;
            $encomienda->encovalorenvio            = $request->valorEnvio;
            $encomienda->encovalordomicilio        = $request->valorDomicilio;
			$encomienda->encocontenido             = mb_strtoupper($request->contenido,'UTF-8');
            $encomienda->encoobservacion           = mb_strtoupper($request->observaciones,'UTF-8');
            $encomienda->encovalorcomisionseguro   = $request->valorSeguro;
            $encomienda->encovalorcomisionempresa  = ($valorTotalEncomienda * $porcentajeComisionEmpresa) / 100;
            $encomienda->encovalorcomisionagencia  = ($valorTotalEncomienda * $porcentajeComisionAgencia) / 100;
            $encomienda->encovalorcomisionvehiculo = ($valorTotalEncomienda * $porcentajeComisionVehiculo) / 100;
            $encomienda->encovalortotal            = $valorTotalEncomienda;
            $encomienda->encopagocontraentrega     = ($request->pagoContraEntrega === 'SI') ? 1 : 0;
            $encomienda->encocontabilizada         = ($request->pagoContraEntrega === 'NO' && $request->contabilizado === 'NO') ? 1 : 0;
			$encomienda->save();

            if($request->pagoContraEntrega === 'NO' && $request->contabilizado === 'NO'){
                //Se realiza la contabilizacion
                $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
                $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                $comprobantecontabledetalle->cueconid        = 1;//Caja
                $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                $comprobantecontabledetalle->cocodemonto     = $valorTotalEncomienda;
                $comprobantecontabledetalle->save();

                $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                $comprobantecontabledetalle->cueconid        = 8; //CXP PAGO ENCOMIENDA
                $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                $comprobantecontabledetalle->cocodemonto     = $valorTotalEncomienda;
                $comprobantecontabledetalle->save();
            }

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador de la encomienda
				$encomiendaConsecutivo                     = Encomienda::latest('encoid')->first();
				$encoid                                    = $encomiendaConsecutivo->encoid;
                $encomiendacambioestado 				   = new EncomiendaCambioEstado();
                $encomiendacambioestado->encoid            = $encoid;
                $encomiendacambioestado->tiesenid          = $estadoEncomienda;
                $encomiendacambioestado->encaesusuaid      = Auth::id();
                $encomiendacambioestado->encaesfechahora   = $fechaHoraActual;
                $encomiendacambioestado->encaesobservacion = 'Registro de la encomienda. Proceso realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $encomiendacambioestado->save();
			}

            $mensajeCorreo  = '';
            if($request->tipo === 'I' && $request->enviarEncomienda === 'SI' && $request->correoRemitente !== ''){//Notifico al correo
                $arrayPdf   = [];
			    array_push($arrayPdf, $this->generarFacturaPdf($encoid, 'F')); 
                $empresa            = DB::table('empresa')->select('emprnombre','emprsigla','emprcorreo')->where('emprid', 1)->first();
                $notificar          = new notificar();
                $informacioncorreo  = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificacionConfirmacionEncomienda')->first();
                $email              = $request->correoRemitente;
                $nombreFeje         = mb_strtoupper($nombreCliente,'UTF-8');
                $buscar             = Array('nombreCliente');
                $remplazo           = Array($nombreCliente);
                $asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
                $msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
                $enviarcopia        = $informacioncorreo->innocoenviarcopia;
                $enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
                $mensajeCorreo      = $notificar->correo([$email], $asunto, $msg, [$arrayPdf], $empresa->emprcorreo, $enviarcopia, $enviarpiepagina);
            }

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito ', 'encomiendaId' => $encoid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function show(Request $request)
    {
		$this->validate(request(),['codigo'  => 'required']);

        $encomienda  = DB::table('encomienda as e')
                                ->select(DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo,' - ', mo.muninombre,' - ', md.muninombre) as nombreRuta"),'e.encovalortotal',
                                 DB::raw("if(e.encopagocontraentrega = 1 ,'SÍ', 'NO') as pagoContraEntrega"),'e.encovalorcomisionseguro','do.depanombre as deptoOrigen', 
                                'mor.muninombre as municipioOrigen', 'dd.depanombre as deptoDestino', 'mde.muninombre as municipioDestino',
                                'te.tipencnombre','e.encocontenido','e.encocantidad','e.encovalordeclarado','e.encovalorenvio','e.encovalordomicilio', 'e.encoobservacion',
                                'psr.tipideid','psr.perserdocumento','psr.perserprimernombre','psr.persersegundonombre','psr.perserprimerapellido',
                                'psr.persersegundoapellido','psr.perserdireccion', 'psr.persercorreoelectronico','psr.persernumerocelular',
                                'psd.tipideid as tipideidDestino','psd.perserdocumento as perserdocumentoDestino','psd.perserprimernombre as perserprimernombreDestino',
                                'psd.persersegundonombre as persersegundonombreDestino','psd.perserprimerapellido as perserprimerapellidoDestino',
                                'psd.persersegundoapellido as persersegundoapellidoDestino','psd.perserdireccion as perserdireccionDestino', 
                                'psd.persercorreoelectronico as persercorreoelectronicoDestino', 'psd.persernumerocelular as persernumerocelularDestino',
                                'tir.tipidenombre as tipoIdentificacionRemitente', 'tid.tipidenombre as tipoIdentificacionDestino')
                                ->join('personaservicio as psr', 'psr.perserid', '=', 'e.perseridremitente')
                                ->join('personaservicio as psd', 'psd.perserid', '=', 'e.perseriddestino')
                                ->join('tipoidentificacion as tir', 'tir.tipideid', '=', 'psr.tipideid')
                                ->join('tipoidentificacion as tid', 'tid.tipideid', '=', 'psd.tipideid')
                                ->join('tipoencomienda as te', 'te.tipencid', '=', 'e.tipencid')
                                ->join('planillaruta as pr', 'pr.plarutid', '=', 'e.plarutid')
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
                                ->join('departamento as do', 'do.depaid', '=', 'e.depaidorigen')
                                ->join('municipio as mor', function($join)
                                {
                                    $join->on('mor.munidepaid', '=', 'e.depaidorigen');
                                    $join->on('mor.muniid', '=', 'e.muniidorigen');
                                })
                                ->join('departamento as dd', 'dd.depaid', '=', 'e.depaiddestino')
                                ->join('municipio as mde', function($join)
                                {
                                    $join->on('mde.munidepaid', '=', 'e.depaiddestino');
                                    $join->on('mde.muniid', '=', 'e.muniiddestino');
                                })
                                ->where('e.encoid', $request->codigo)->first();

        $cambiosEstadoEncomienda = DB::table('encomiendacambioestado as ece')
                                    ->select('ece.encaesfechahora as fecha','ece.encaesobservacion as observacion','tee.tiesennombre as estado',
                                        DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                    ->join('tipoestadoencomienda as tee', 'tee.tiesenid', '=', 'ece.tiesenid')
                                    ->join('encomienda as e', 'e.encoid', '=', 'ece.encoid')
                                    ->join('usuario as u', 'u.usuaid', '=', 'ece.encaesusuaid')
                                    ->where('e.encoid', $request->codigo)
                                    ->orderBy('encaesid')->get();

        return response()->json(["encomienda" => $encomienda, "cambiosEstadoEncomienda" => $cambiosEstadoEncomienda ]);
    }

    public function verFactura(Request $request)
    {
		$this->validate(request(),['codigo' => 'required']);
		try{
  			return response()->json(["data" => Encomienda::generarFacturaPdf($request->codigo, 'S') ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }
	}  

    public function obtenerConsecutivo($anioActual)
	{
        $consecutivoEncomienda = DB::table('encomienda')->select('encoconsecutivo as consecutivo')
                                                        ->where('agenid', auth()->user()->agenid)
                                                        ->where('encoanio', $anioActual)->orderBy('encoid', 'desc')->first();

        $consecutivo = ($consecutivoEncomienda === null) ? 1 : $consecutivoEncomienda->consecutivo + 1;
        return str_pad($consecutivo,  4, "0", STR_PAD_LEFT);
    }
}