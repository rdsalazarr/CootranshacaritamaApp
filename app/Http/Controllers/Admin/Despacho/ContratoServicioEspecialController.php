<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\ContratoServicioEspecialConductor;
use App\Models\Despacho\ContratoServicioEspecialVehiculo;
use App\Models\Despacho\PersonaContratoServicioEspecial;
use App\Models\Despacho\ContratoServicioEspecial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\generales;
use Exception, DB;

class ContratoServicioEspecialController extends Controller
{
    public function index(Request $request)
    {
		$this->validate(request(),['tipo'   => 'required']);

        $data = DB::table('contratoservicioespecial as cse')->select('cse.coseesid','cse.coseesfechaincial','cse.coseesfechafinal','cse.coseesorigen','cse.coseesdestino',                                    
                                    DB::raw("CONCAT(cse.coseesanio, cse.coseesconsecutivo) as numeroContrato"),
                                    DB::raw("CONCAT(pcse.pecoseprimernombre,' ',if(pcse.pecosesegundonombre is null ,'', pcse.pecosesegundonombre),' ',
                                            pcse.pecoseprimerapellido,' ',if(pcse.pecosesegundoapellido is null ,' ', pcse.pecosesegundoapellido)) as nombreResponsable"))
                                    ->join('pecoseonacontratoservicioesp as pcse', 'pcse.pecoseid', '=', 'cse.pecoseid')
                                    ->orderBy('cse.coseesid', 'Desc')->get();
        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        $contratoservicioespecial  = [];
        $personaContrato           = [];
        $contratoVehiculo          = [];
        $contratoConductor         = [];

        $tipoContratosServicioEspecial = DB::table('tipocontratoservicioespecial')->select('ticoseid','ticosenombre')->orderBy('ticosenombre')->get();
        $tipoConveniosServicioEspecial = DB::table('tipoconvenioservicioespecial')->select('ticossid','ticossnombre')->orderBy('ticossnombre')->get();
		$tipoIdentificaciones          = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4','5'])->orderBy('tipidenombre')->get();

		$conductores = DB::table('persona as p')->select('c.condid', 
									DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
										p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreConductor"))		
									->join('conductor as c', 'c.persid', '=', 'p.persid')
									->where('c.tiescoid', 'A')
									->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
									->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();

		$vehiculos = DB::table('vehiculo as v')->select('v.vehiid',DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
									->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
									->where('v.tiesveid', 'A')
									//->where('v.tiseveid', 'E')
									->orderBy('v.vehinumerointerno')->get();

        return response()->json(["tipoContratosServicioEspecial" => $tipoContratosServicioEspecial, "tipoConveniosServicioEspecial" => $tipoConveniosServicioEspecial,
                                "contratoservicioespecial"       => $contratoservicioespecial,      "personaContrato"               => $personaContrato, 
                                "contratoVehiculo"               => $contratoVehiculo,              "contratoConductor"             => $contratoConductor,
								"tipoIdentificaciones"           => $tipoIdentificaciones,          "conductores"                   => $conductores,
								"vehiculos"                      => $vehiculos]);
    }

    public function salve(Request $request)
	{
        $depeid      = $request->id;
        $dependencia = ($depeid != 000) ? Dependencia::findOrFail($depeid) : new Dependencia();

	    $this->validate(request(),[
	   			'codigo'          => 'required|string|min:1|max:10|unique:dependencia,depecodigo,'.$dependencia->depeid.',depeid',
                'sigla'           => 'required|string|min:1|max:3|unique:dependencia,depesigla,'.$dependencia->depeid.',depeid',
	   	        'nombre'          => 'required|string|min:4|max:80',
                'correo'          => 'required|string|email|min:4|max:80',
                'jefeDependencia' => 'required',
	            'estado'          => 'required',
                'subSeries'       => 'required|array|min:1',
                'personas'        => 'required|array|min:1'
	        ]);

        DB::beginTransaction();
        try {
            $dependencia->depejefeid = $request->jefeDependencia;
            $dependencia->depecodigo = $request->codigo;
            $dependencia->depesigla  = mb_strtoupper($request->sigla,'UTF-8');
            $dependencia->depenombre = mb_strtoupper($request->nombre,'UTF-8');
            $dependencia->depecorreo = $request->correo;
            $dependencia->depeactiva = $request->estado;
            $dependencia->save();

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador de la dependencia
				$dependenciaConsecutivo    = Dependencia::latest('depeid')->first();
				$depeid                  = $dependenciaConsecutivo->depeid;
			}

            foreach($request->subSeries as $subserie){
				$identificador = $subserie['identificador'];
				$subSerie      = $subserie['subSerie'];
				$personaEstado = $subserie['estado'];
				if($personaEstado === 'I'){
					$dependenciasubseriedocumental = new DependenciaSubSerieDocumental();
					$dependenciasubseriedocumental->desusddepeid   = $depeid;
					$dependenciasubseriedocumental->desusdsusedoid = $subSerie;
					$dependenciasubseriedocumental->save();
				}else if($personaEstado === 'D'){
					$dependenciasubseriedocumental = DependenciaSubSerieDocumental::findOrFail($identificador);
					$dependenciasubseriedocumental->delete();
				}else{
					$dependenciasubseriedocumental = DependenciaSubSerieDocumental::findOrFail($identificador);
					$dependenciasubseriedocumental->desusddepeid   = $depeid;
					$dependenciasubseriedocumental->desusdsusedoid = $subSerie;
					$dependenciasubseriedocumental->save();
				}
			}

            foreach($request->personas as $dataPersona){
				$identificador = $dataPersona['identificador'];
				$persona       = $dataPersona['persona'];
				$personaEstado = $dataPersona['estado'];
				if($personaEstado === 'I'){
					$dependenciapersona = new DependenciaPersona();
					$dependenciapersona->depperdepeid  = $depeid;
					$dependenciapersona->depperpersid  = $persona;
					$dependenciapersona->save();
				}else if($personaEstado === 'D'){
					$dependenciapersona = DependenciaPersona::findOrFail($identificador);
					$dependenciapersona->delete();
				}else{
					$dependenciapersona = DependenciaPersona::findOrFail($identificador);
					$dependenciapersona->depperdepeid  = $depeid;
					$dependenciapersona->depperpersid  = $persona;
					$dependenciapersona->save();
				}
			}
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
			$generales  = new generales();  
			$generarPdf = new generarPdf();
			$arrayDatos = [ 
							"numeroContratoEspecial"       => '45400830220230693',
							"numeroContratoCompletoUno"    => '454008302202306930781',
							"numeroContratoCompletoDos"    => '454008302202306930782',
							"numeroContrato"               => '0693',
							"numeroExtracto"               => '0781', 
							"nombreContratante"            => 'COLEGIO CRISTIANO LUZ Y VIDA', 
							"documentoContratante"         => '37313214_8',
							"objetoContrato"               => 'La prestación del servicio de transporte de los estudiantes entre el lugar de residencia y el establecimiento educativo u otros destinos que se requieran en razón de las actividades programadas por el plantel educativo, según el decreto 0348 del 245 de febrero de 2015',
							"origenContrato"               => 'OCAÑA ( N DE S)',
							"destinoContrato"              => 'CASCO URBANO DE OCAÑA', 
							"descripcionRecorrido"         => 'CASCO URBANO DE OCAÑA',
							"convenioContrato"             => 'X',
							"consorcioContrato"            => '',
							"unionTemporal"                => '',
							"nombreUnionTemporal"          => '',
							"placaVehiculo"                => 'TTR122',
							"modeloVehiculo"               => '2013',
							"marcaVehiculo"                => 'NISSAN',
							"claseVehiculo"                => 'MICROBUS',
							"numeroInternoVehiculo"        => '486',
							"tarjetaOperacionVehiculo"     => '282487',
							"nombreContratante"            => 'ELIZABETH LOPEZ BARBOSA',
							"documentoContratante"         => '37313214',
							"direccionContratante"         => 'BARRIO EL CENTRO',
							"telefonoContratante"          => '3103006860',
							"firmaGerente"                 => 'archivos/persona/5036123/firma_5036123.png',
							"nombreGerente"                => 'LUIS MANUEL ASCANIO CLARO',
							"documentoGerente"             => '37.336.963',
							"valorContrato"                => '1,500,000',
							"fechaInicialContrato"         => $generales->formatearFechaContratoServicioEspecial('2023-11-01'),
							"fechaFinalContrato"           => $generales->formatearFechaContratoServicioEspecial('2023-11-30'),
							"descripcionServicoContratado" => 'DOS (2) vehículo(s) con número(s) interno(s) 473, 486 con 16, 16 puestos',
							"idCifrado"                    => '123',
							"metodo"                       => 'S'
						];

			$arrayVigenciaContrato = [];
			$fechaInicio = [
							"dia"  => '01',
							"mes"  => '11',
							"anio" => '2023',
						];        
			$fechaFin = [
						"dia"  => '30',
						"mes"  => '11',
						"anio" => '2023',
					];        
			array_push($arrayVigenciaContrato, $fechaInicio, $fechaFin);

			$arrayConductores  = [];
			$conductor = [
						"nombreCompleto" => 'ELAIN MACHADO DOMINGUEZ ELAIN MACHADO DOMINGUEZ',
						"documento"      => '88284528',
						"numeroLicencia" => '88284528',
						"vigencia"       => '2025-10-04',
						]; 
			array_push($arrayConductores, $conductor);

			$conductor = [
							"nombreCompleto" => 'HUBERNEY SALAZAR AMAYA',
							"documento"      => '10648419387',
							"numeroLicencia" => '10648419387',
							"vigencia"       => '2025-09-26',
							]; 
			array_push($arrayConductores, $conductor);

			$conductor = [
							"nombreCompleto" => 'DEIVER MORA GARCIA',
							"documento"      => '1977858',
							"numeroLicencia" => '1977858',
							"vigencia"       => '2025-02-08',
							]; 
			array_push($arrayConductores, $conductor);

			$data = $generarPdf->contratoServicioEspecial($arrayDatos, $arrayVigenciaContrato, $arrayConductores);
  			return response()->json(["data" => $data ]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$e->getMessage()]);
        }

	}
	
}