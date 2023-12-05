<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\ContratoServicioEspecialConductor;
use App\Models\Despacho\ContratoServicioEspecialVehiculo;
use App\Models\Despacho\PersonaContratoServicioEspecial;
use App\Models\Despacho\ContratoServicioEspecial;
use App\Http\Controllers\Controller;
use App\Util\generarPlanilla;
use Illuminate\Http\Request;
use App\Util\notificar;
use Carbon\Carbon;
use Exception, DB;

class ContratoServicioEspecialController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['tipo' => 'required']);
		$fechaHoraActual = Carbon::now();
		$fechaActual     = $fechaHoraActual->format('Y-m-d');

        $consulta = DB::table('contratoservicioespecial as cse')->select('cse.coseesid','cse.coseesfechaincial','cse.coseesfechafinal','cse.coseesorigen','cse.coseesdestino', 
                                    DB::raw("CONCAT(cse.coseesanio, cse.coseesconsecutivo) as numeroContrato"),
                                    DB::raw("CONCAT(pcse.pecoseprimernombre,' ',if(pcse.pecosesegundonombre is null ,'', pcse.pecosesegundonombre),' ',
                                            pcse.pecoseprimerapellido,' ',if(pcse.pecosesegundoapellido is null ,' ', pcse.pecosesegundoapellido)) as nombreResponsable"))
                                    ->join('personacontratoservicioesp as pcse', 'pcse.pecoseid', '=', 'cse.pecoseid');

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
											->join('personacontratoservicioesp as pcse', 'pcse.pecoseid', '=', 'cse.pecoseid')
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

        $data     = DB::table('personacontratoservicioesp')
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
				'documento'            => 'required|string|min:6|max:15|unique:personacontratoservicioesp,pecosedocumento,'.$personaContratoServicioEspecial->pecoseid.',pecoseid',
				'primerNombre'         => 'required|string|min:3|max:140',
				'segundoNombre'        => 'nullable|string|min:3|max:40',
				'primerApellido'       => 'required|string|min:4|max:40',
				'segundoApellido'      => 'nullable|string|min:4|max:40',
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
			$generarPlanilla     = new generarPlanilla();
			$fechaHoraActual     = Carbon::now();
            $anioActual          = Carbon::now()->year;
			$nombreResponsable   = mb_strtoupper($request->primerNombre.' '.$request->segundoNombre.' '.$request->primerApellido.' '.$request->segundoApellido,'UTF-8');

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
				$empresa                                     = DB::table('empresa')->select('persidrepresentantelegal', 'emprcorreo')->where('emprid', '1')->first();
				$correoDependencia						     = $empresa->emprcorreo;
				$numeroPlanilla                              = $this->obtenerConsecutivoContrato($anioActual);
				$contratoServicioEspecial->persidgerente     = $empresa->persidrepresentantelegal;
				$contratoServicioEspecial->pecoseid          = $pecoseid;
				$contratoServicioEspecial->coseesfechahora   = $fechaHoraActual;
				$contratoServicioEspecial->coseesanio        = $anioActual;
				$contratoServicioEspecial->coseesconsecutivo = $numeroPlanilla;
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
				$arrayPdf     = [];
				array_push($arrayPdf, $generarPlanilla->servicioEspecial($coseesid, 'F'));

				$notificar         = new notificar();
				$informacioncorreo = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarRegistroServicioEspecial')->first();
				$buscar            = Array('numeroPlanilla', 'nombreResponsable');
				$remplazo          = Array($numeroPlanilla, $nombreResponsable); 
				$innocoasunto      = $informacioncorreo->innocoasunto;
				$innococontenido   = $informacioncorreo->innococontenido;
				$enviarcopia       = $informacioncorreo->innocoenviarcopia;
				$enviarpiepagina   = $informacioncorreo->innocoenviarpiepagina;
				$asunto            = str_replace($buscar, $remplazo, $innocoasunto);
				$msg               = str_replace($buscar, $remplazo, $innococontenido);
				$mensajeNotificar = ', se ha enviado notificación a '.$notificar->correo([$correoPersona], $asunto, $msg, [$arrayPdf], $correoDependencia, $enviarcopia, $enviarpiepagina);
			}
            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito'.$mensajeNotificar, 'planillaId' => $coseesid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function verPlanilla(Request $request)
    {
		$this->validate(request(),['codigo'   => 'required']);
		try{
			$generarPlanilla = new generarPlanilla();
			$data            = $generarPlanilla->servicioEspecial($request->codigo, 'S');
  			return response()->json(["data" => $data ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }
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