<?php

namespace App\Http\Controllers\Admin\AtencionUsuario;

use App\Models\Radicacion\DocumentoEntranteCambioEstado;
use App\Models\Radicacion\DocumentoEntranteDependencia;
use App\Models\Radicacion\PersonaRadicaDocumento;
use App\Models\Radicacion\DocumentoEntranteAnexo;
use App\Models\Radicacion\DocumentoEntrante;
use App\Models\AtencionUsuario\Solicitud;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Exception, Auth, DB, File;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon;

class GestionarSolicitudController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);
         
        $consulta   = DB::table('radicaciondocumentoentrante as rde')
                    ->select('rde.radoenid as id', 'rde.radoenfechahoraradicado as fechaRadicado','rde.radoenasunto as asunto',
                        DB::raw("CONCAT(rde.radoenanio,' - ', rde.radoenconsecutivo) as consecutivo"),'d.depenombre as dependencia','terde.tierdenombre as estado',
                        DB::raw("CONCAT(prd.peradoprimernombre,' ',if(prd.peradosegundonombre is null ,'', prd.peradosegundonombre),' ', prd.peradoprimerapellido,' ',if(prd.peradosegundoapellido is null ,' ', prd.peradosegundoapellido)) as nombrePersonaRadica"))
                    ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                    ->join('radicaciondocentdependencia as rded', function($join)
                        {
                            $join->on('rded.radoenid', '=', 'rde.radoenid');
                            $join->where('rded.radoedescopia', false); 
                        })
                    ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                    ->join('tipoestadoraddocentrante as terde', 'terde.tierdeid', '=', 'rde.tierdeid');

                    if($request->tipo === 'PRODUCIR')
                        $consulta = $consulta->where('rde.tierdeid', 1);

                    if($request->tipo === 'VERIFICAR')
                        $consulta = $consulta->where('rde.tierdeid', '!=', 1);

                    if($request->tipo === 'HISTORICO')
                        $consulta = $consulta->where('rde.radoenanio', '!=', Carbon::now()->year);

                $data = $consulta->orderBy('rde.radoenid', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required']);	
		$codigo            = $request->codigo;
		$tipo              = $request->tipo;
        $data              = []; 
        $anexosRadicados   = [];
        if($tipo === 'U'){
            $data   = DB::table('radicaciondocumentoentrante as rde')
                        ->select('rde.peradoid','rde.tipmedid','rde.tierdeid','rde.depaid','rde.muniid','rded.depeid','rded.radoedid','rde.radoenfechadocumento',
                                'rde.radoenfechallegada','rde.radoenpersonaentregadocumento','rde.radoenasunto','rde.radoentieneanexo',
                                'rde.radoendescripcionanexo','rde.radoentienecopia','rde.radoenobservacion',
                                'prd.tipideid','prd.peradodocumento','prd.peradoprimernombre','prd.peradosegundonombre','prd.peradoprimerapellido',
                                'prd.peradosegundoapellido', 'prd.peradodireccion','prd.peradotelefono','prd.peradocorreo','prd.peradocodigodocumental',
                                DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid AND radoedescopia = true) AS totalCopias'),
                                DB::raw('(SELECT COUNT(radoeaid) AS radoeaid FROM radicaciondocentanexo WHERE radoenid = rde.radoenid AND radoearequiereradicado = false ) AS totalAnexos'))
                        ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                        ->join('radicaciondocentdependencia as rded', function($join)
                            {
                                $join->on('rded.radoenid', '=', 'rde.radoenid');
                                $join->where('rded.radoedescopia', false); 
                            })
                        ->where('rde.radoenid', $codigo)->first();

            $anexosRadicados  =  DB::table('radicaciondocentanexo as rdea')
                                ->select('rdea.radoeaid as id','rdea.radoeanombreanexooriginal as nombreOriginal','rdea.radoeanombreanexoeditado as nombreEditado',
                                'rdea.radoearutaanexo as rutaAnexo',DB::raw("if(rdea.radoearequiereradicado = 1 ,'Sí', 'No') as radicarDocumento"), 'rde.radoenanio as anio',
                                DB::raw("CONCAT('archivos/radicacion/documentoEntrante/',rde.radoenanio,'/', rdea.radoearutaanexo) as rutaDescargar"))
                                ->join('radicaciondocumentoentrante as rde', 'rde.radoenid', '=', 'rdea.radoenid')
                                ->where('rdea.radoenid', $codigo)->get();
        }

        $fechaHoraActual       = Carbon::now()->format('Y-m-d h:m:s');
        $tipoMedios            = DB::table('tipomediosolicitud')->select('timesoid','timesonombre')->orderBy('timesonombre')->get();
		$tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();
        $tipoSolicitudes       = DB::table('tiposolicitud')->select('tipsolid','tipsolnombre')->orderBy('tipsolnombre')->get();
        $vehiculos             = DB::table('vehiculo as v')->select('v.vehiid', DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
                                ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                ->where('v.tiesveid', 'A')
                                ->where('v.agenid', auth()->user()->agenid)->get();

        $conductores           = DB::table('conductorvehiculo as cv')
                                    ->select('c.condid',
                                    DB::raw("CONCAT(p.persdocumento,' ', p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreConductor"))
                                    ->join('conductor as c', 'c.condid', '=', 'cv.condid')
                                    ->join('persona as p', 'p.persid', '=', 'c.persid')
                                    ->where('c.tiescoid', 'A')->get();

        return response()->json(["fechaHoraActual" => $fechaHoraActual, "tipoMedios"      => $tipoMedios,     "tipoIdentificaciones" => $tipoIdentificaciones, 
                                "tipoSolicitudes"  => $tipoSolicitudes,	"vehiculos"       => $vehiculos,      "conductores"          => $conductores,
                                "data"             => $data,            "anexosRadicados" => $anexosRadicados ]);
	}

    public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'numeroIdentificacion' => 'required|string|max:15']);

        $data     = DB::table('personaradicadocumento')
                            ->select('peradoid','tipideid','peradodocumento','peradoprimernombre','peradosegundonombre',
                            'peradoprimerapellido','peradosegundoapellido', 'peradodireccion','peradotelefono','peradocorreo')
                            ->where('tipideid', $request->tipoIdentificacion)
                            ->where('peradodocumento', $request->numeroIdentificacion)->first();

        return response()->json(['success' => ($data !== null) ? true : false, 'data' => $data]);
    }

    public function salve(Request $request)
	{
	    $this->validate(request(),[
                'codigo'                  => 'required',
                'tipo'                    => 'required',
                'tipoIdentificacion'      => 'required|numeric',
                'numeroIdentificacion'    => 'required|string|max:15',
                'primerNombre'            => 'required|string|min:4|max:70',
                'segundoNombre'           => 'nullable|string|min:4|max:40',
                'primerApellido'          => 'nullable|string|min:4|max:40',
                'segundoApellido'         => 'nullable|string|min:4|max:40',
                'direccionFisica'         => 'required|string|min:4|max:100',
                'correoElectronico'       => 'nullable|email|string|max:80',
                'numeroContacto'          => 'nullable|string|max:20',

                'fechaHoraIncidente'      => 'required|date|date_format:Y-m-d H:i:s',
                'tipoSolicitud'           => 'required',
                'tipoMedio'               => 'required',
                'vehiculoId'              => 'nullable|numeric',
                'conductorId'             => 'nullable|numeric',
                'observacionGeneral'      => 'nullable|string|min:4|max:1000',
                'motivoSolicitud'         => 'required|string|min:4|max:2000',
                'personaId'               => 'nullable|numeric',

                'archivos'                => 'nullable|array|max:2000',
                'archivos.*'              => 'nullable|mimes:jpg,png,jpeg,doc,docx,pdf,ppt,pptx,xls,xlsx,xlsm,zip,rar|max:2000',
	        ]);

        DB::beginTransaction();
        try {


           /*  $anioActual              = Carbon::now()->year;         
            $rutaCarpeta             = public_path().'/archivos/radicacion/documentoEntrante/'.$anioActual;
            $rutaPdf        = $rutaCarpeta.'/14815_2024_Formato_PQRS.pdf';
            $nombreArchivo = '14815_2024_Formato_PQRS.pdf';
            $dataCopias     = [];
            $dataRadicado   = DB::table('radicaciondocumentoentrante as rde')
                                ->select('rde.radoenfechahoraradicado  as fechaRadicado', DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),
                                        'd.depenombre as dependencia','u.usuaalias as usuario', 'prd.peradocorreo  as correo',    'rde.radoenasunto as asunto')
                                ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                                ->join('radicaciondocentdependencia as rded', function($join)
                                    {
                                        $join->on('rded.radoenid', '=', 'rde.radoenid');
                                        $join->where('rded.radoedescopia', false);
                                    })
                                ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                ->join('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                                ->where('rde.radoenid', 5)->first();        
            $generarPdf              = new generarPdf();
            $generarPdf->radicarDocumentoExterno($rutaCarpeta, $nombreArchivo, $dataRadicado, $dataCopias, true);

            dd("voy aca ");*/

            $estado                  = '1'; //Recibido
            $fechaHoraActual         = Carbon::now();
            $anioActual              = Carbon::now()->year;
            $fechaActual             = $fechaHoraActual->format('Y-m-d');
            $funcion 		         = new generales();
            $generarPdf              = new generarPdf();
            $radoenid                = $request->codigo;
            $numeroAleatorio         = rand(1000, 100000);
            $nombreArchivo           = $numeroAleatorio.''.$anioActual.'_Formato_PQRS.pdf';
            $rutaCarpeta             = public_path().'/archivos/radicacion/documentoEntrante/'.$anioActual;
            $personaEntregaDocumento = mb_strtoupper($request->primerNombre.' '.$request->segundoNombre.' '.$request->primerApellido.' '.$request->segundoApellido,'UTF-8');
            $asuntoRadicado          = 'Se registró una queja por parte de '.$personaEntregaDocumento;
            $asuntoRadicado          .= ($request->vehiculoNombre !== null) ?', en la que también se vio involucrado el vehículo '.$request->vehiculoNombre : ',';
            $asuntoRadicado          .= ($request->conductorNombre !== null) ?', y el conductor '.$request->conductorNombre : '.';
            $asuntoRadicado          .= ' Este proceso fue radicado por '.auth()->user()->usuanombre.' '.auth()->user()->usuaapellidos;
    
            //Genero el PDF que voy se va a radicar
            $arrayDatos = [ 
                    "tipoIdentificacion"     => $request->tipoIdentificacionNombre,
                    "documentoIdentidad"     => $request->numeroIdentificacion,
                    "nombresSolicitante"     => $request->primerNombre.' '.$request->segundoNombre,
                    "apellidosSolicitante"   => $request->primerApellido.' '.$request->segundoApellido,
                    "direccionSolicitante"   => $request->direccionFisica,
                    "telefonoSolicitante"    => $request->numeroContacto,
                    "correoSolicitante"      => $request->correoElectronico,
                    "tipoSolicitud"          => $request->tipoSolicitudNombre,
                    "tipoMedio"              => $request->tipoMedioNombre,
                    "fechaRegistro"          => $fechaActual,
                    "fechaIncidente"         => $request->fechaHoraIncidente,
                    "conductorInvolucrado"   => $request->conductorNombre,
                    "vehiculoInvolucrado"    => $request->vehiculoNombre,
                    "motivoSolicitud"        => $request->motivoSolicitud,
                    "observacionesSolicitud" => $request->observacionGeneral,
                    "anioRadicado"           => $anioActual,
                    "nombreArchivo"          => $nombreArchivo,
                    "metodo"                 => 'F'
                ];    
            
            $dependencia    =  DB::table('empresa as e')->select('d.depeid','d.depecorreo',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreGerente"))
                                    ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                    ->leftjoin('dependencia as d', 'd.depejefeid', '=', 'p.persid')
                                    ->where('emprid', '1')->first();
            $dependenciaId = $dependencia->depeid;
            if($dependenciaId === null){
                return response()->json(['success' => false, 'message'=> 'No se encontró una dependencia asignada a la persona que se está designando como gerente de la cooperativa']);
            }
            
            $peradoid                                       = ($request->personaId !== '000') ? $request->personaId : '000';
            $personaradicadocumento                         = ($peradoid != '000') ? PersonaRadicaDocumento::findOrFail($peradoid) : new PersonaRadicaDocumento();
            $personaradicadocumento->tipideid               = $request->tipoIdentificacion;
            $personaradicadocumento->peradodocumento        = $request->numeroIdentificacion;
            $personaradicadocumento->peradoprimernombre     = mb_strtoupper($request->primerNombre,'UTF-8');
            $personaradicadocumento->peradosegundonombre    = mb_strtoupper($request->segundoNombre,'UTF-8');
            $personaradicadocumento->peradoprimerapellido   = mb_strtoupper($request->primerApellido,'UTF-8');
            $personaradicadocumento->peradosegundoapellido  = mb_strtoupper($request->segundoApellido,'UTF-8');
            $personaradicadocumento->peradodireccion        = $request->direccionFisica; 
            $personaradicadocumento->peradotelefono         = $request->numeroContacto; 
            $personaradicadocumento->peradocorreo           = $request->correoElectronico;
            $personaradicadocumento->save();
   
            if($request->tipo === 'I' and $request->personaId === '000'){//Consulto el ultimo identificador de la persona 
                $perRadDocumentoMaxConsecutio  = PersonaRadicaDocumento::latest('peradoid')->first();
                $peradoid                      = $perRadDocumentoMaxConsecutio->peradoid;
            }

            $radicaciondocumentoentrante       = ($request->tipo === 'U') ? DocumentoEntrante::findOrFail($radoenid) : new DocumentoEntrante();
            if($request->tipo === 'I'){
                $radicaciondocumentoentrante->tierdeid                   = $estado;
                $radicaciondocumentoentrante->usuaid                     = Auth::id();
                $radicaciondocumentoentrante->radoenconsecutivo          = DocumentoEntrante::obtenerConsecutivo($anioActual);
                $radicaciondocumentoentrante->radoenanio                 = $anioActual;
                $radicaciondocumentoentrante->radoenfechahoraradicado    = $fechaHoraActual;
                $radicaciondocumentoentrante->radoenfechamaximarespuesta = $funcion->obtenerFechaMaxima(15, Carbon::now()->format('Y-m-d'));
            }

            $radicaciondocumentoentrante->peradoid                      = $peradoid;
            $radicaciondocumentoentrante->tipmedid                      = 1;//lo marco como impreso
            $radicaciondocumentoentrante->depaid                        = 18;//Marco por defecto a norte de santander
            $radicaciondocumentoentrante->muniid                        = 804;//Marco por defecto a ocaña
            $radicaciondocumentoentrante->radoenfechadocumento          = $fechaActual;
            $radicaciondocumentoentrante->radoenfechallegada            = $fechaActual;
            $radicaciondocumentoentrante->radoenpersonaentregadocumento = $personaEntregaDocumento;
            $radicaciondocumentoentrante->radoenasunto                  = $asuntoRadicado;
            $radicaciondocumentoentrante->radoentieneanexo              = ($request->hasFile('archivos')) ? 1 : 0;
            $radicaciondocumentoentrante->radoenobservacion             = $request->observacionGeneral;
            $radicaciondocumentoentrante->save();

            if($request->tipo === 'I'){
                //Consulto el ultimo identificador de la persona
                $radDocumentoMaxConsecutio      = DocumentoEntrante::latest('radoenid')->first();
                $radoenid                       = $radDocumentoMaxConsecutio->radoenid;

                $radicaciondocentdependencia           = new DocumentoEntranteDependencia();
                $radicaciondocentdependencia->radoenid = $radoenid;
                $radicaciondocentdependencia->depeid   = $dependenciaId;//Se debe marcar por defecto gerencia
                $radicaciondocentdependencia->save();

                $radicaciondocentanexo                            = new DocumentoEntranteAnexo();
                $radicaciondocentanexo->radoenid                  = $radoenid;
                $radicaciondocentanexo->radoeanombreanexooriginal = $nombreArchivo;
                $radicaciondocentanexo->radoeanombreanexoeditado  = $nombreArchivo;
                $radicaciondocentanexo->radoearutaanexo           = Crypt::encrypt($nombreArchivo);
                $radicaciondocentanexo->radoearequiereradicado    = true;
                $radicaciondocentanexo->save();

                //Almaceno la trazabilidad del radicado
                $radicaciondocentcambioestado 					 = new DocumentoEntranteCambioEstado();
                $radicaciondocentcambioestado->radoenid          = $radoenid;
                $radicaciondocentcambioestado->tierdeid          = $estado;
                $radicaciondocentcambioestado->radeceusuaid      = Auth::id();
                $radicaciondocentcambioestado->radecefechahora   = $fechaHoraActual;
                $radicaciondocentcambioestado->radeceobservacion = 'Documento radicado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $radicaciondocentcambioestado->save();
            }

            //Registramos los adjuntos
			if($request->hasFile('archivos')){
				$numeroAleatorio = rand(100, 1000);
				$funcion         = new generales();
                $rutaCarpeta     = public_path().'/archivos/radicacion/documentoEntrante/'.$anioActual;
                $carpetaServe    = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
				$files           = $request->file('archivos');
				foreach($files as $file){
					$nombreOriginal = $file->getclientOriginalName();
					$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
					$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo  = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
					$file->move($rutaCarpeta, $nombreArchivo);
					$rutaArchivo    = Crypt::encrypt($nombreArchivo);

					$radicaciondocentanexo                            = new DocumentoEntranteAnexo();
					$radicaciondocentanexo->radoenid                  = $radoenid;
					$radicaciondocentanexo->radoeanombreanexooriginal = $nombreOriginal;
					$radicaciondocentanexo->radoeanombreanexoeditado  = $nombreArchivo;
					$radicaciondocentanexo->radoearutaanexo           = $rutaArchivo;
                    $radicaciondocentanexo->radoearequiereradicado    = false;
					$radicaciondocentanexo->save();
				}
			}

//soliid
            $solicitud 					        = new Solicitud();
            if($request->tipo === 'I'){
                $solicitud->solifechahoraregistro   = $fechaHoraActual;
                $solicitud->soliradicado            = true;
            }
            $solicitud->peradoid                = $peradoid;
            $solicitud->radoenid                = $radoenid;
            $solicitud->tipsolid                = $request->tipoSolicitud;
            $solicitud->timesoid                = $request->tipoMedio;
            $solicitud->vehiid                  = $request->vehiculoId;
            $solicitud->condid                  = $request->conductorId; 
            $solicitud->solifechahoraincidente  = $request->fechaHoraIncidente;
            $solicitud->solimotivo              = $request->motivoSolicitud;
            $solicitud->soliobservacion         = $request->observacionGeneral;
            $solicitud->save();

            if($request->tipo === 'I'){//Radico el documento
                $rutaPdfRadicar = $generarPdf->generarFormatoSolicitud($arrayDatos);//Descargamos el pdf para radicarlo
                $rutaPdf        = $rutaCarpeta.'/'.$nombreArchivo;
                $dataCopias     = [];
                $dataRadicado   = DB::table('radicaciondocumentoentrante as rde')
                                    ->select('rde.radoenfechahoraradicado  as fechaRadicado', DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),
                                            'd.depenombre as dependencia','u.usuaalias as usuario', 'prd.peradocorreo  as correo',    'rde.radoenasunto as asunto')
                                    ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                                    ->join('radicaciondocentdependencia as rded', function($join)
                                        {
                                            $join->on('rded.radoenid', '=', 'rde.radoenid');
                                            $join->where('rded.radoedescopia', false);
                                        })
                                    ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                    ->join('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                                    ->where('rde.radoenid', $radoenid)->first();        

               // $generarPdf->radicarDocumentoExterno($rutaCarpeta, $nombreArchivo, $dataRadicado, $dataCopias, true);
            }

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', 'idRadicado' => $radoenid]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}
