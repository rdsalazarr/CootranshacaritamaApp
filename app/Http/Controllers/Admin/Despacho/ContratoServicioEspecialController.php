<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\ContratoServicioEspecialConductor;
use App\Models\Despacho\ContratoServicioEspecialVehiculo;
use App\Models\Despacho\PersonaContratoServicioEspecial;
use App\Models\Despacho\ContratoServicioEspecial;
use App\Http\Controllers\Controller;
use App\Util\convertirNumeroALetras;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;

class ContratoServicioEspecialController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['tipo'   => 'required']);
		$fechaHoraActual = Carbon::now();
		$fechaActual     = $fechaHoraActual->format('Y-m-d');

        $consulta = DB::table('contratoservicioespecial as cse')->select('cse.coseesid','cse.coseesfechaincial','cse.coseesfechafinal','cse.coseesorigen','cse.coseesdestino', 
                                    DB::raw("CONCAT(cse.coseesanio, cse.coseesconsecutivo) as numeroContrato"),
                                    DB::raw("CONCAT(pcse.pecoseprimernombre,' ',if(pcse.pecosesegundonombre is null ,'', pcse.pecosesegundonombre),' ',
                                            pcse.pecoseprimerapellido,' ',if(pcse.pecosesegundoapellido is null ,' ', pcse.pecosesegundoapellido)) as nombreResponsable"))
                                    ->join('pecoseonacontratoservicioesp as pcse', 'pcse.pecoseid', '=', 'cse.pecoseid');

									if($request->tipo === 'ACTIVOS')
										$consulta = $consulta->whereDate('cse.coseesfechaincial', '>=', $fechaActual);

									if($request->tipo === 'HISTORICO')
										$consulta = $consulta->whereDate('cse.coseesfechaincial', '<', $fechaActual);

           $data = $consulta->orderBy('cse.coseesid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        $contratoServicioEspecial      = [];
        $contratoVehiculos             = [];
        $contratoConductores           = [];
        $tipoContratosServicioEspecial = DB::table('tipocontratoservicioespecial')->select('ticoseid','ticosenombre')->orderBy('ticosenombre')->get();
        $tipoConveniosServicioEspecial = DB::table('tipoconvenioservicioespecial')->select('ticossid','ticossnombre')->orderBy('ticossnombre')->get();
		$tipoIdentificaciones          = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4','5'])->orderBy('tipidenombre')->get();

		$conductores = DB::table('persona as p')->select('c.condid', 
									DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
										p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"))
									->join('conductor as c', 'c.persid', '=', 'p.persid')
									->where('c.tiescoid', 'A')
									->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
									->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();

		$vehiculos = DB::table('vehiculo as v')->select('v.vehiid',DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
									->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
									->where('v.tiesveid', 'A')
									->where('v.timoveid', '7')//Tipo modalidad especial
									->orderBy('v.vehinumerointerno')->get();

		if($request->tipo === 'U'){
			$contratoServicioEspecial = DB::table('contratoservicioespecial as cse')
											->select('cse.coseesid','cse.pecoseid','cse.ticoseid','cse.ticossid','cse.coseesfechaincial','cse.coseesvalorcontrato','cse.coseesnombreuniontemporal',
													'cse.coseesfechafinal','cse.coseesorigen','cse.coseesdestino','cse.coseesdescripcionrecorrido','cse.coseesobservacion',
													'pcse.tipideid','pcse.pecosedocumento','pcse.pecoseprimernombre','pcse.pecosesegundonombre','pcse.pecoseprimerapellido',
													'pcse.pecosesegundoapellido','pcse.pecosedireccion', 'pcse.pecosecorreoelectronico','pcse.pecosenumerocelular')
											->join('pecoseonacontratoservicioesp as pcse', 'pcse.pecoseid', '=', 'cse.pecoseid')
											->where('cse.coseesid', $request->codigo)->first();

			$contratoVehiculos   = DB::table('contratoservicioespecialvehi')->select('coseevid', 'vehiid')->where('coseesid', $request->codigo)->get();
			$contratoConductores = DB::table('contratoservicioespecialcond')->select('coseecod', 'condid')->where('coseesid', $request->codigo)->get();
		}

        return response()->json(["tipoContratosServicioEspecial" => $tipoContratosServicioEspecial, "tipoConveniosServicioEspecial" => $tipoConveniosServicioEspecial,
                                "contratoServicioEspecial"       => $contratoServicioEspecial,      "contratoVehiculos"              => $contratoVehiculos,            
								"contratoConductores"            => $contratoConductores,           "tipoIdentificaciones"          => $tipoIdentificaciones,          
								"conductores"                    => $conductores,                   "vehiculos"                     => $vehiculos]);
    }

	public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'documento' => 'required|string|max:15']);
//personacontratoservicioesp
        $data     = DB::table('pecoseonacontratoservicioesp')
                            ->select('pecoseid','tipideid','pecosedocumento','pecoseprimernombre','pecosesegundonombre','pecoseprimerapellido',
                            			'pecosesegundoapellido','pecosedireccion', 'pecosecorreoelectronico','pecosenumerocelular')
                            ->where('tipideid', $request->tipoIdentificacion)
                            ->where('pecosedocumento', $request->documento)->first();

        return response()->json(['success' => ($data !== null) ? true : false, 'data' => $data]);
    }

    public function salve(Request $request)
	{
        $coseesid                        = $request->codigo;
		$pecoseid                        = $request->personaId;		
        $contratoServicioEspecial        = ($coseesid != 000) ? ContratoServicioEspecial::findOrFail($coseesid) : new ContratoServicioEspecial();
		$personaContratoServicioEspecial = ($pecoseid != 000) ? PersonaContratoServicioEspecial::findOrFail($pecoseid) : new PersonaContratoServicioEspecial();

	    $this->validate(request(),[
			    'tipoIdentificacion'   => 'required|numeric',
				'documento'            => 'required|string|min:6|max:15|unique:pecoseonacontratoservicioesp,pecosedocumento,'.$personaContratoServicioEspecial->pecoseid.',pecoseid',
				'primerNombre'         => 'required|string|min:3|max:140',
				'segundoNombre'        => 'nullable|string|min:3|max:40',
				'primerApellido'       => 'required|string|min:4|max:40',
				'segundoApellido'      => 'nullable|string|min:4|max:40',
				'fechaNacimiento' 	   => 'nullable|date|date_format:Y-m-d',
				'direccion'            => 'required|string|min:4|max:100',
				'correo'               => 'nullable|email|string|max:80',
				'telefonoCelular'      => 'nullable|string|max:20',
				'tipoConvenio'         => 'required|string',
				'tipoContrato'         => 'required|string',
				'fechaInicial' 	       => 'required|date|date_format:Y-m-d',
				'fechaFinal' 	       => 'required|date|date_format:Y-m-d',
				'valorContrato'        => 'required|numeric',
				'origen'               => 'required|string|max:100',
				'destino'              => 'required|string|max:100',
				'descripcionRecorrido' => 'required|string|max:1000',
				'observaciones'        => 'nullable|string|max:1000',
				'nombreUnionTemporal' => 'nullable|string|max:100|required_if:tipoConvenio,UT',				 
                'vehiculos'            => 'required|array|min:1',
                'conductores'          => 'required|array|min:1'
	        ]);

        DB::beginTransaction();
        try {
			$fechaHoraActual     = Carbon::now();
            $anioActual          = Carbon::now()->year;

			$personaContratoServicioEspecial->tipideid                = $request->tipoIdentificacion;
			$personaContratoServicioEspecial->pecosedocumento         = $request->documento;
			$personaContratoServicioEspecial->pecoseprimernombre      = mb_strtoupper($request->primerNombre,'UTF-8');
			$personaContratoServicioEspecial->pecosesegundonombre     = mb_strtoupper($request->segundoNombre,'UTF-8');
			$personaContratoServicioEspecial->pecoseprimerapellido    = mb_strtoupper($request->primerApellido,'UTF-8');
			$personaContratoServicioEspecial->pecosesegundoapellido   = mb_strtoupper($request->segundoApellido,'UTF-8');
			$personaContratoServicioEspecial->pecosedireccion         = $request->direccion;
			$personaContratoServicioEspecial->pecosecorreoelectronico = $request->correo;
			$personaContratoServicioEspecial->pecosenumerocelular     = $request->telefonoCelular;
			$personaContratoServicioEspecial->save();

            if($request->tipo === 'I' and $pecoseid === 000){
				//Consulto el ultimo identificador de la persona del contrato
				$personaContratoConsecutivo = PersonaContratoServicioEspecial::latest('pecoseid')->first();
				$pecoseid                   = $personaContratoConsecutivo->pecoseid;
			}

			if($request->tipo === 'I'){
				$empresa                                     = DB::table('empresa')->select('persidrepresentantelegal')->where('emprid', '1')->first();
				$contratoServicioEspecial->persidgerente     = $empresa->persidrepresentantelegal;
				$contratoServicioEspecial->pecoseid          = $pecoseid;
				$contratoServicioEspecial->coseesfechahora   = $fechaHoraActual;
				$contratoServicioEspecial->coseesanio        = $anioActual;
				$contratoServicioEspecial->coseesconsecutivo = $this->obtenerConsecutivoContrato($anioActual);;
			}

			$contratoServicioEspecial->ticoseid                   = $request->tipoContrato;
			$contratoServicioEspecial->ticossid                   = $request->tipoConvenio;
			$contratoServicioEspecial->coseesfechaincial          = $request->fechaInicial;
			$contratoServicioEspecial->coseesfechafinal           = $request->fechaFinal;
			$contratoServicioEspecial->coseesvalorcontrato        = $request->valorContrato;
			$contratoServicioEspecial->coseesorigen               = mb_strtoupper($request->origen,'UTF-8');
			$contratoServicioEspecial->coseesdestino              = mb_strtoupper($request->destino,'UTF-8');
			$contratoServicioEspecial->coseesdescripcionrecorrido = mb_strtoupper($request->descripcionRecorrido,'UTF-8');
			$contratoServicioEspecial->coseesnombreuniontemporal  = $request->nombreUnionTemporal;
			$contratoServicioEspecial->coseesobservacion          = mb_strtoupper($request->observaciones,'UTF-8');
			$contratoServicioEspecial->save();

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador del contrato
				$contratoConsecutivo = ContratoServicioEspecial::latest('coseesid')->first();
				$coseesid            = $contratoConsecutivo->coseesid;
			}
			
            foreach($request->vehiculos as $vehiculo){
				$identificador  = $vehiculo['identificador'];
				$vehiculoId     = $vehiculo['vehiculoId'];
				$vehiculoEstado = $vehiculo['estado'];
				if($vehiculoEstado === 'I'){
					$contratoservicioespecialvehi = new ContratoServicioEspecialVehiculo();
					$contratoservicioespecialvehi->coseesid                  = $coseesid;
					$contratoservicioespecialvehi->vehiid                    = $vehiculoId;
					$contratoservicioespecialvehi->coseevextractoanio        = $anioActual;
					$contratoservicioespecialvehi->coseevextractoconsecutivo = $this->obtenerConsecutivoVehiculo($anioActual);
					$contratoservicioespecialvehi->save();
				}else if($vehiculoEstado === 'D'){
					$contratoservicioespecialvehi = ContratoServicioEspecialVehiculo::findOrFail($identificador);
					$contratoservicioespecialvehi->delete();
				}else{ // Omitir
				}
			}

            foreach($request->conductores as $conductor){
				$identificador   = $conductor['identificador'];
				$conductorId     = $conductor['conductorId'];
				$conductorEstado = $conductor['estado'];
				if($conductorEstado === 'I'){
					$contratoservicioespecialcond = new ContratoServicioEspecialConductor();
					$contratoservicioespecialcond->coseesid = $coseesid;
					$contratoservicioespecialcond->condid   = $conductorId;
					$contratoservicioespecialcond->save();
				}else if($conductorEstado === 'D'){
					$contratoservicioespecialcond = ContratoServicioEspecialConductor::findOrFail($identificador);
					$contratoservicioespecialcond->delete();
				}else{// Omitir
				}
			}

			$mensajeNotificar = '';
			if($request->tipo === 'I' and $request->correo !== ''){
				//Registramos la notificacion
				$correoPersona = $request->correo;

				$notificar          = new notificar();
				/*$informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarRegistroServicioEspecial')->first();				
				$buscar            = Array('numeroRadicado', 'nombreUsuario', 'nombreEmpresa', 'fechaRadicado','nombreDependencia','nombreFuncionario','nombreDependencia');
				$remplazo          = Array($numeroRadicado, $nombreUsuario, $nombreEmpresa,  $fechaRadicado, $nombreDependencia, $nombreFuncionario, $nombreDependencia); 
				$innocoasunto      = $informacioncorreo->innocoasunto;
				$innococontenido   = $informacioncorreo->innococontenido;
				$enviarcopia       = $informacioncorreo->innocoenviarcopia;
				$enviarpiepagina   = $informacioncorreo->innocoenviarpiepagina;
				$asunto            = str_replace($buscar, $remplazo, $innocoasunto);
				$msg               = str_replace($buscar, $remplazo, $innococontenido);
				$mensajeNotificar = ', se ha enviado notificacion a '.$notificar->correo([$correoPersona], $asunto, $msg, [], $correoDependencia, $enviarcopia, $enviarpiepagina);
				*/
			}
            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito '.$mensajeNotificar, 'planillaId' => $coseesid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function verPlanilla(Request $request)
    {
		$this->validate(request(),['codigo'   => 'required']);
		try{
			$data = $this->visualizarPlanilla($request->codigo, 'S');			
  			return response()->json(["data" => $data ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }
	}

	public function visualizarPlanilla($coseesid, $metodo)
	{
		$convertirNumeroALetras = new convertirNumeroALetras();
		$generales  			= new generales();  
		$generarPdf 			= new generarPdf();
		$url        			= URL::to('/');

		$contratoServicioEspecial = DB::table('contratoservicioespecial as cse')
										->select('cse.coseesid','cse.pecoseid','cse.ticossid','cse.coseesanio','cse.coseesconsecutivo','cse.coseesfechaincial','cse.coseesvalorcontrato',
												'cse.coseesfechafinal','cse.coseesorigen','cse.coseesdestino','cse.coseesdescripcionrecorrido','cse.coseesobservacion', 'cse.coseesnombreuniontemporal',
												'pcse.pecosedocumento',	'pcse.tipideid','pcse.pecosedireccion', 'pcse.pecosenumerocelular','p.persdocumento',
												DB::raw("CONCAT(pcse.pecoseprimernombre,' ',if(pcse.pecosesegundonombre is null ,'', pcse.pecosesegundonombre),' ',
												pcse.pecoseprimerapellido,' ',if(pcse.pecosesegundoapellido is null ,' ', pcse.pecosesegundoapellido)) as nombreContratante"),
												DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
												p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreGerente"),
												DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaGerente"),
												)
										->join('pecoseonacontratoservicioesp as pcse', 'pcse.pecoseid', '=', 'cse.pecoseid')
										->join('persona as p', 'p.persid', '=', 'cse.persidgerente')
										->where('cse.coseesid', $coseesid)->first();
		$anioContrato   = $contratoServicioEspecial->coseesanio;
		$numeroContrato = $contratoServicioEspecial->coseesconsecutivo;

		$contratoVehiculos   = DB::table('contratoservicioespecialvehi as csev')
								->select('csev.coseevid','csev.coseevextractoanio', 'csev.coseevextractoconsecutivo','v.vehiplaca','v.vehimodelo','v.vehinumerointerno', 'tmv.timavenombre', 'tv.tipvehnombre', 'tv.tipvecapacidad','vto.vetaopnumero')
								->join('vehiculo as v', 'v.vehiid', '=', 'csev.vehiid')
								->join('tipomarcavehiculo as tmv', 'tmv.timaveid', '=', 'v.timaveid')
								->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
								->leftJoin('vehiculotarjetaoperacion as vto', function ($join) {
									$join->on('vto.vehiid', '=', 'v.vehiid')
										->where('vto.vetaopfechafinal', '=', DB::raw('(SELECT MAX(vetaopfechafinal) FROM vehiculotarjetaoperacion WHERE vehiid = v.vehiid)'));
								})
								->where('csev.coseesid', $coseesid)->get();
		$contador          = 0;
		$numerosInternos   = '';
		$capacidadVehiculo = '';
		foreach($contratoVehiculos as $contratoVehiculo){
			$numerosInternos .= $contratoVehiculo->vehinumerointerno.', ';
			$capacidadVehiculo .= $contratoVehiculo->tipvecapacidad.', ';
			$contador ++;
		}

		$servicoContratado = ($contador > 1 ) ? $convertirNumeroALetras->valorEnLetras($contador).'('.$contador.') vehículo(s) con número(s) interno(s) '.substr($numerosInternos, 0, -2).' con '.substr($capacidadVehiculo, 0, -2).' puestos' : 'Un vehículo con número interno '. substr($numerosInternos, 0, -2).' de '.substr($capacidadVehiculo, 0, -2).' puestos';
		$objetoContrato    = 'La realización de un servicio de transporte expreso para trasladar a todas las personas que hacen parte del grupo desde un origen ';
		$objetoContrato    .= 'determinado en el presente contrato hasta el destino determinado en el presente contrato, según el decreto 0348 del 245 de febrero de 2015.';

		$arrayDatos = [ 
						"numeroContratoEspecial"       => '454008302'.$anioContrato.''.$numeroContrato,
						"numeroContrato"               => $numeroContrato,
						"nombreContratante"            => $contratoServicioEspecial->nombreContratante,
						"documentoContratante"         => number_format($contratoServicioEspecial->pecosedocumento,0,',','.'),
						"direccionContratante"         => $contratoServicioEspecial->pecosedireccion,
						"telefonoContratante"          => $contratoServicioEspecial->pecosenumerocelular,
						"objetoContrato"               => $objetoContrato,
						"origenContrato"               => $contratoServicioEspecial->coseesorigen,
						"destinoContrato"              => $contratoServicioEspecial->coseesdestino,
						"descripcionRecorrido"         => $contratoServicioEspecial->coseesdescripcionrecorrido,
						"valorContrato"                => number_format($contratoServicioEspecial->coseesvalorcontrato,0,',','.'),
						"fechaInicialContrato"         => $generales->formatearFechaContratoServicioEspecial($contratoServicioEspecial->coseesfechaincial),
						"fechaFinalContrato"           => $generales->formatearFechaContratoServicioEspecial($contratoServicioEspecial->coseesfechafinal),
						"descripcionServicoContratado" => $servicoContratado,
						"firmaGerente"                 => $contratoServicioEspecial->firmaGerente,
						"nombreGerente"                => $contratoServicioEspecial->nombreGerente,
						"documentoGerente"             => number_format($contratoServicioEspecial->persdocumento,0,',','.'),
						"idCifrado"                    => $contratoServicioEspecial->coseesid,
						"convenioContrato"             => ($contratoServicioEspecial->ticossid === 'CV') ? 'X' : '',
						"consorcioContrato"            => ($contratoServicioEspecial->ticossid === 'CS') ? 'X' : '',
						"unionTemporal"                => ($contratoServicioEspecial->ticossid === 'UT') ? 'X' : '',
						"nombreUnionTemporal"          => ($contratoServicioEspecial->ticossid === 'UT') ? $contratoServicioEspecial->coseesnombreuniontemporal : '',
						"observaciones"                => $contratoServicioEspecial->coseesobservacion,
						"metodo"                       => $metodo
					];

		$arrayVigenciaContrato = [];
		$fechaIncial           = explode('-',$contratoServicioEspecial->coseesfechaincial);
		$fechaFinal            = explode('-',$contratoServicioEspecial->coseesfechafinal);
		$fechaInicio 		   = [
									"dia"  => $fechaIncial[2],
									"mes"  => $fechaIncial[1],
									"anio" => $fechaIncial[0]
								];

		$fechaFin 				= [
									"dia"  => $fechaFinal[2],
									"mes"  => $fechaFinal[1],
									"anio" => $fechaFinal[0]
								];
		array_push($arrayVigenciaContrato, $fechaInicio, $fechaFin);

		$arrayConductores = DB::table('contratoservicioespecialcond as csec')
									->select('p.persdocumento as documento','cl.conlicnumero as numeroLicencia', 'cl.conlicfechavencimiento as vigencia',
										DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
										p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreCompleto")
									)
									->join('conductor as c', 'c.condid', '=', 'csec.condid')
									->join('persona as p', 'p.persid', '=', 'c.persid')
									->join('conductorlicencia as cl', function ($join) {
										$join->on('cl.condid', '=', 'c.condid')
											->where('cl.conlicfechavencimiento', '=', DB::raw('(SELECT MAX(conlicfechavencimiento) FROM conductorlicencia WHERE condid = c.condid)'));
									})
									->where('coseesid', $coseesid)
									->get();
		
		return $generarPdf->contratoServicioEspecial($arrayDatos, $arrayVigenciaContrato, $contratoVehiculos, $arrayConductores);
	}

	public function obtenerConsecutivoContrato($anioActual)
	{
		$consecutivoContrato = DB::table('contratoservicioespecial')->select('coseesconsecutivo as consecutivo')
								->where('coseesanio', $anioActual)->orderBy('coseesid', 'desc')->first();
        $consecutivo = ($consecutivoContrato === null) ? 1 : $consecutivoContrato->consecutivo + 1;
		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}

	public function obtenerConsecutivoVehiculo($anioActual)
	{
		$consecutivoVehiculo = DB::table('contratoservicioespecialvehi')->select('coseevextractoconsecutivo as consecutivo')
								->where('coseevextractoanio', $anioActual)->orderBy('coseevid', 'desc')->first();
        $consecutivo = ($consecutivoVehiculo === null) ? 1 : $consecutivoVehiculo->consecutivo + 1;
		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}
}