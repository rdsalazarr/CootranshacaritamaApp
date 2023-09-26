<?php

namespace App\Util;

use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoCertificado;
use App\Models\CodigoDocumentalProcesoConstancia;
use App\Models\CodigoDocumentalProcesoCompartido;
use App\Models\CodigoDocumentalProcesoCircular;
use App\Models\CodigoDocumentalProcesoCitacion;
use App\Models\CodigoDocumentalProcesoOficio;
use App\Models\CodigoDocumentalProcesoFirma;
use App\Models\CodigoDocumentalProcesoAnexo;
use App\Models\CodigoDocumentalProcesoCopia;
use App\Models\CodigoDocumentalProcesoActa;
use App\Models\CodigoDocumentalProceso;
use Illuminate\Support\Facades\Crypt;
use App\Models\CodigoDocumental;
use App\Util\generales;
use Auth, DB, File;
use Carbon\Carbon;

class manejadorDocumentos {

	public function consultarInformacionMaestra($tipoDocumental, $depeid){	
		
		$tipoActas               = [];
		$tipoDestinos            = [];
		$tipoSaludos    		 = [];
		$tipoPersonaDocumentales = [];
		$tipoDespedidas  		 = []; 
		$dependencias    		 = [];

		//Actas y citacion	
		if($tipoDocumental === 'A'  || $tipoDocumental === 'H'){
			$tipoActas       = DB::table('tipoacta')->select('tipactid','tipactnombre')->orderBy('tipactnombre')->get();
		}

		//certificado y constancia
		if($tipoDocumental === 'C'  || $tipoDocumental === 'T'){
			$tipoDestinos            = DB::table('tipodestino')->select('tipdetid','tipdetnombre')->orderBy('tipdetnombre')->get();
			$tipoPersonaDocumentales = DB::table('tipopersonadocumental')->select('tipedoid','tipedonombre')->where('tipedoactivo', true)->orderBy('tipedonombre')->get();	
		}

		//Circular y oficio
		if($tipoDocumental === 'C'  || $tipoDocumental === 'O'){
			$tipoDestinos    = DB::table('tipodestino')->select('tipdetid','tipdetnombre')->orderBy('tipdetnombre')->get();		
			$tipoSaludos     = DB::table('tiposaludo')->select('tipsalid','tipsalnombre')->orderBy('tipsalnombre')->get();
			$tipoDespedidas  = DB::table('tipodespedida')->select('tipdesid','tipdesnombre')->orderBy('tipdesnombre')->get();
			$dependencias    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeactiva', true)->where('depeid', '!=', $depeid)->orderBy('depenombre')->get();
		}

		//Lo requieren todos
		$fechaActual     = Carbon::now()->format('Y-m-d');
		$tipoMedios      = DB::table('tipomedio')->select('tipmedid','tipmednombre')->whereIn('tipmedid', [1,2,3])->orderBy('tipmednombre')->get();
		$personas        = DB::table('persona')->select('persid',DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre),' ', persprimerapellido,' ',if(perssegundoapellido is null ,' ', perssegundoapellido)) as nombrePersona"))
														->orderBy('nombrePersona')
														->whereIn('carlabid', [1, 2])->get();
        $cargoLaborales  = DB::table('cargolaboral')->select('carlabid','carlabnombre')->orderBy('carlabnombre')->whereIn('carlabid', [1, 2])->get();

		return array ($fechaActual, $tipoDestinos, $tipoMedios, $tipoSaludos, $tipoDespedidas, $dependencias, $personas, $cargoLaborales, $tipoActas, $tipoPersonaDocumentales);
	}

   
    public function acta($request){

        $coddocid      				   = $request->idCD;
	    $codoprid      				   = $request->idCDP;
	    $codopaid      				   = $request->idCDPA;
        $codigodocumental              = ($coddocid != 000) ? CodigoDocumental::findOrFail($coddocid) : new CodigoDocumental();
		$codigodocumentalproceso       = ($codoprid != 000) ? CodigoDocumentalProceso::findOrFail($codoprid) : new CodigoDocumentalProceso();
		$codigodocumentalprocesoacta   = ($codopaid != 000) ? CodigoDocumentalProcesoActa::findOrFail($codopaid) : new CodigoDocumentalProcesoActa();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid', $request->dependencia)->first();
			$sigla          = $dependencia->depesigla;
			
			if($request->tipo === 'I'){
				$codigodocumental->depeid          = $request->dependencia;
				$codigodocumental->serdocid        = $request->serie;
				$codigodocumental->susedoid        = $request->subSerie;
				$codigodocumental->tipdocid        = '1';//Acta
				$codigodocumental->tiptraid        = $request->tipoTramite;
				$codigodocumental->usuaid          = $usuarioId;
				$codigodocumental->coddocfechahora = $fechaHoraActual;
			}
			$codigodocumental->tipmedid            = $request->tipoMedio;
			$codigodocumental->tipdetid            = $request->tipoDestino;
		   	$codigodocumental->save();

			if($request->tipo === 'I'){
				//Consulto el ultimo identificador de los codigos documentales
				$codDocMaxConsecutio               = CodigoDocumental::latest('coddocid')->first();
				$coddocid                          = $codDocMaxConsecutio->coddocid;
				$codigodocumentalproceso->coddocid = $coddocid;
	    		$codigodocumentalproceso->tiesdoid = '1'; //Inicial
			}

	    	$codigodocumentalproceso->codoprfecha               = $request->fecha;
	    	$codigodocumentalproceso->codoprnombredirigido      = $request->asistentes;
	    	$codigodocumentalproceso->codoprcorreo              = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido           = $request->contenido;
	    	$codigodocumentalproceso->save();  

			if($request->tipo === 'I'){
				$codDocProcesoMaxConsecutio 					= CodigoDocumentalProceso::latest('codoprid')->first();
				$codoprid                   					= $codDocProcesoMaxConsecutio->codoprid;
				$codigodocumentalprocesoacta->codoprid          = $codoprid;
				$codigodocumentalprocesoacta->usuaid            = $usuarioId;
				$codigodocumentalprocesoacta->codopaconsecutivo = $this->obtenerConsecutivo($sigla, $anioActual, 'A');
				$codigodocumentalprocesoacta->codopasigla       = $sigla;
				$codigodocumentalprocesoacta->codopaanio        = $anioActual;
			}

			$codigodocumentalprocesoacta->tipactid                = $request->tipoActa;
			$codigodocumentalprocesoacta->codopahorainicio        = $request->horaInicial;
		   	$codigodocumentalprocesoacta->codopahorafinal         = $request->horaFinal;
		   	$codigodocumentalprocesoacta->codopalugar             = $request->lugar;
		   	$codigodocumentalprocesoacta->codopaquorum            = $request->quorum;
		   	$codigodocumentalprocesoacta->codopaordendeldia       = $request->ordenDia;
		   	$codigodocumentalprocesoacta->codopainvitado          = $request->invitados;
			$codigodocumentalprocesoacta->codopaausente           = $request->ausentes;
			$codigodocumentalprocesoacta->codopaconvocatoria      = $request->convocatoria;
			$codigodocumentalprocesoacta->codopaconvocatorialugar = $request->convocatoriaLugar;
			$codigodocumentalprocesoacta->codopaconvocatoriafecha = $request->convocatoriaFecha;
			$codigodocumentalprocesoacta->codopaconvocatoriahora  = $request->convocatoriaHora;
		   	$codigodocumentalprocesoacta->save();		
		
			foreach($request->firmaPersonas as $firmaPersona){
				$identificadorFirma = $firmaPersona['identificador'];
				$personaFirma       = $firmaPersona['persona'];
				$personaCargo       = $firmaPersona['cargo'];
				$personaEstado      = $firmaPersona['estado'];
				if($personaEstado === 'I'){
					$coddocumprocesofirma = new CodigoDocumentalProcesoFirma();
					$coddocumprocesofirma->codoprid  = $codoprid;
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}else if($personaEstado === 'D'){
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->delete();
				}else{
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}
			}		

			if($request->tipo === 'I'){
				//Almaceno la trazabilidad del documento
				$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
				$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
				$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
				$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
				$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
				$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->usuanombre;
				$codigodocumentalprocesocambioestado->save(); 
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function certificado($request){

        $coddocid      				   = $request->idCD;
	    $codoprid      				   = $request->idCDP;
	    $codopcid      				   = $request->idCDPC;
        $codigodocumental              = ($coddocid != 000) ? CodigoDocumental::findOrFail($coddocid) : new CodigoDocumental();
		$codigodocumentalproceso       = ($codoprid != 000) ? CodigoDocumentalProceso::findOrFail($codoprid) : new CodigoDocumentalProceso();
		$coddocumprocesocertificado    = ($codopcid != 000) ? CodigoDocumentalProcesoCertificado::findOrFail($codopcid) : new CodigoDocumentalProcesoCertificado();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid', $request->dependencia)->first();
			$sigla          = $dependencia->depesigla;

			if($request->tipo === 'I'){
				$codigodocumental->depeid          = $request->dependencia;
				$codigodocumental->serdocid        = $request->serie;
				$codigodocumental->susedoid        = $request->subSerie;
				$codigodocumental->tipdocid        = '2';//Certificado
				$codigodocumental->tiptraid        = $request->tipoTramite;
				$codigodocumental->usuaid          = $usuarioId;
				$codigodocumental->coddocfechahora = $fechaHoraActual;
			}
			$codigodocumental->tipmedid            = $request->tipoMedio;
			$codigodocumental->tipdetid            = $request->tipoDestino;
		   	$codigodocumental->save();

			if($request->tipo === 'I'){
				//Consulto el ultimo identificador de los codigos documentales
				$codDocMaxConsecutio               = CodigoDocumental::latest('coddocid')->first();
				$coddocid                          = $codDocMaxConsecutio->coddocid;
				$codigodocumentalproceso->coddocid = $coddocid;
	    		$codigodocumentalproceso->tiesdoid = '1'; //Inicial
			}
	    	
	    	$codigodocumentalproceso->codoprfecha            = $request->fecha;
	    	$codigodocumentalproceso->codoprnombredirigido   = $request->nombreDirigido;
	    	$codigodocumentalproceso->codoprcorreo           = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido        = $request->contenido;
	    	$codigodocumentalproceso->save();  

			if($request->tipo === 'I'){
				$codDocProcesoMaxConsecutio 				  = CodigoDocumentalProceso::latest('codoprid')->first();
				$codoprid                   				  = $codDocProcesoMaxConsecutio->codoprid;
				$coddocumprocesocertificado->codoprid          = $codoprid;
				$coddocumprocesocertificado->usuaid            = $usuarioId;
				$coddocumprocesocertificado->codopcconsecutivo = $this->obtenerConsecutivo($sigla, $anioActual, 'B');
				$coddocumprocesocertificado->codopcsigla       = $sigla;
				$coddocumprocesocertificado->codopcanio        = $anioActual;
			}

			$coddocumprocesocertificado->tipedoid                = $request->tipoPersona;
		   	$coddocumprocesocertificado->codopctitulo            = $request->tituloDocumento;
		   	$coddocumprocesocertificado->codopccontenidoinicial  = $request->contenidoInicial;
		   	$coddocumprocesocertificado->save();

			foreach($request->firmaPersona as $firmaPersona){
				$identificadorFirma = $firmaPersona['identificador'];
				$personaFirma       = $firmaPersona['persona'];
				$personaCargo       = $firmaPersona['cargo'];
				$personaEstado      = $firmaPersona['estado'];
				if($personaEstado === 'I'){
					$coddocumprocesofirma = new CodigoDocumentalProcesoFirma();
					$coddocumprocesofirma->codoprid  = $codoprid;
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}else if($personaEstado === 'D'){
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->delete();
				}else{
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}
			}

			if($request->tipo === 'I'){
				//Almaceno la trazabilidad del documento
				$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
				$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
				$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
				$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
				$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
				$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->usuanombre;
				$codigodocumentalprocesocambioestado->save(); 
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function circular($request){

        $coddocid      			 = $request->idCD;
	    $codoprid      			 = $request->idCDP;
	    $codoplid      			 = $request->idCDPC;
        $codigodocumental        = ($coddocid != 000) ? CodigoDocumental::findOrFail($coddocid) : new CodigoDocumental();
		$codigodocumentalproceso = ($codoprid != 000) ? CodigoDocumentalProceso::findOrFail($codoprid) : new CodigoDocumentalProceso();
		$coddocumprocesocircular = ($codoplid != 000) ? CodigoDocumentalProcesoCircular::findOrFail($codoplid) : new CodigoDocumentalProcesoCircular();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid', $request->dependencia)->first();
			$sigla          = $dependencia->depesigla;
			
			if($request->tipo === 'I'){
				$codigodocumental->depeid          = $request->dependencia;
				$codigodocumental->serdocid        = $request->serie;
				$codigodocumental->susedoid        = $request->subSerie;
				$codigodocumental->tipdocid        = '3';//Circular
				$codigodocumental->tiptraid        = $request->tipoTramite;
				$codigodocumental->usuaid          = $usuarioId;
				$codigodocumental->coddocfechahora = $fechaHoraActual;
			}
			$codigodocumental->tipmedid            = $request->tipoMedio;
			$codigodocumental->tipdetid            = $request->tipoDestino;
		   	$codigodocumental->save();

			if($request->tipo === 'I'){
				//Consulto el ultimo identificador de los codigos documentales
				$codDocMaxConsecutio               = CodigoDocumental::latest('coddocid')->first();
				$coddocid                          = $codDocMaxConsecutio->coddocid;
				$codigodocumentalproceso->coddocid = $coddocid;
	    		$codigodocumentalproceso->tiesdoid = '1'; //Inicial
			}
	    	
	    	$codigodocumentalproceso->codoprfecha               = $request->fecha;
	    	$codigodocumentalproceso->codoprnombredirigido      = $request->destinatarios;
	      	$codigodocumentalproceso->codoprasunto              = $request->asunto;
	    	$codigodocumentalproceso->codoprcorreo              = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido           = $request->contenido;
	    	$codigodocumentalproceso->codoprtieneanexo          = $request->tieneAnexo;
	    	$codigodocumentalproceso->codopranexonombre         = $request->nombreAnexo;
	    	$codigodocumentalproceso->codoprtienecopia          = $request->tieneCopia;
	    	$codigodocumentalproceso->codoprcopianombre         = $request->nombreCopia;
	    	$codigodocumentalproceso->save();

			if($request->tipo === 'I'){
				$codDocProcesoMaxConsecutio 				= CodigoDocumentalProceso::latest('codoprid')->first();
				$codoprid                   				= $codDocProcesoMaxConsecutio->codoprid;
				$coddocumprocesocircular->codoprid          = $codoprid;
				$coddocumprocesocircular->usuaid            = $usuarioId;
				$coddocumprocesocircular->codoplconsecutivo = $this->obtenerConsecutivo($sigla, $anioActual, 'C');
				$coddocumprocesocircular->codoplsigla       = $sigla;
				$coddocumprocesocircular->codoplanio        = $anioActual;
			}

			$coddocumprocesocircular->tipdesid  = $request->despedida;
		   	$coddocumprocesocircular->save();

			//Registramos los adjuntos
			if($request->hasFile('archivos')){
				$numeroAleatorio = rand(100, 1000);
				$funcion         = new generales();
				$rutaCarpeta     = public_path().'/archivos/produccionDocumental/adjuntos/'.$sigla.'/'.$anioActual;
				$carpetaServe    = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
				$files           = $request->file('archivos');
				foreach($files as $file){
					$nombreOriginal = $file->getclientOriginalName();
					$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
					$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo  = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
					$file->move($rutaCarpeta, $nombreArchivo);
					$rutaArchivo = Crypt::encrypt($nombreArchivo);

					$coddocumprocesoanexo = new CodigoDocumentalProcesoAnexo();
					$coddocumprocesoanexo->codoprid                  = $codoprid;
					$coddocumprocesoanexo->codopxnombreanexooriginal = $nombreOriginal;
					$coddocumprocesoanexo->codopxnombreanexoeditado  = $nombreArchivo;
					$coddocumprocesoanexo->codopxrutaanexo           = $rutaArchivo;
					$coddocumprocesoanexo->save();
				}
			}

			foreach($request->firmaPersonas as $firmaPersona){
				$identificadorFirma = $firmaPersona['identificador'];
				$personaFirma       = $firmaPersona['persona'];
				$personaCargo       = $firmaPersona['cargo'];
				$personaEstado      = $firmaPersona['estado'];
				if($personaEstado === 'I'){
					$coddocumprocesofirma = new CodigoDocumentalProcesoFirma();
					$coddocumprocesofirma->codoprid  = $codoprid;
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}else if($personaEstado === 'D'){
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->delete();
				}else{
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}
			}

			if($request->tipo === 'U'){
				//Elimino las dependencia que esten en el documento
				$coddocumprocesocopiaConsultas = DB::table('coddocumprocesocopia')->select('codoppid')->where('codoprid', $codoprid)->get();
				foreach($coddocumprocesocopiaConsultas as $coddocumprocesocop){
					$coddocumprocesocopiaDelete = CodigoDocumentalProcesoCopia::findOrFail($coddocumprocesocop->codoppid);
					$coddocumprocesocopiaDelete->delete();
				}
			}

			if($request->copiasDependencia !== null){
				foreach($request->copiasDependencia as $copiaDependencia){
					$coddocumprocesocopia                         = new CodigoDocumentalProcesoCopia();
					$coddocumprocesocopia->codoprid               = $codoprid;
					$coddocumprocesocopia->depeid                 = $copiaDependencia['depeid'];
					$coddocumprocesocopia->codoppescopiadocumento = true;
					$coddocumprocesocopia->save();
				}
			}

			if($request->tipo === 'I'){
				//Almaceno la trazabilidad del documento
				$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
				$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
				$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
				$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
				$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
				$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->usuanombre;
				$codigodocumentalprocesocambioestado->save(); 
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function citacion($request){
        $coddocid      			 = $request->idCD;
	    $codoprid      			 = $request->idCDP;
	    $codoptid      			 = $request->idCDPC;
        $codigodocumental        = ($coddocid != 000) ? CodigoDocumental::findOrFail($coddocid) : new CodigoDocumental();
		$codigodocumentalproceso = ($codoprid != 000) ? CodigoDocumentalProceso::findOrFail($codoprid) : new CodigoDocumentalProceso();
		$coddocumprocesocitacion = ($codoptid != 000) ? CodigoDocumentalProcesoCitacion::findOrFail($codoptid) : new CodigoDocumentalProcesoCitacion();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid', $request->dependencia)->first();
			$sigla          = $dependencia->depesigla;
			
			if($request->tipo === 'I'){
				$codigodocumental->depeid          = $request->dependencia;
				$codigodocumental->serdocid        = $request->serie;
				$codigodocumental->susedoid        = $request->subSerie;
				$codigodocumental->tipdocid        = '4';//Citacion
				$codigodocumental->tiptraid        = $request->tipoTramite;
				$codigodocumental->usuaid          = $usuarioId;
				$codigodocumental->coddocfechahora = $fechaHoraActual;
			}
			$codigodocumental->tipmedid            = $request->tipoMedio;
			$codigodocumental->tipdetid            = $request->tipoDestino;
		   	$codigodocumental->save();

			if($request->tipo === 'I'){
				//Consulto el ultimo identificador de los codigos documentales
				$codDocMaxConsecutio               = CodigoDocumental::latest('coddocid')->first();
				$coddocid                          = $codDocMaxConsecutio->coddocid;
				$codigodocumentalproceso->coddocid = $coddocid;
	    		$codigodocumentalproceso->tiesdoid = '1'; //Inicial
			}

	    	$codigodocumentalproceso->codoprfecha          = $request->fecha;
	    	$codigodocumentalproceso->codoprcorreo         = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido      = $request->contenido;
	    	$codigodocumentalproceso->save();

			if($request->tipo === 'I'){
				$codDocProcesoMaxConsecutio 				= CodigoDocumentalProceso::latest('codoprid')->first();
				$codoprid                   				= $codDocProcesoMaxConsecutio->codoprid;
				$coddocumprocesocitacion->codoprid          = $codoprid;
				$coddocumprocesocitacion->usuaid            = $usuarioId;
				$coddocumprocesocitacion->codoptconsecutivo = $this->obtenerConsecutivo($sigla, $anioActual, 'H');
				$coddocumprocesocitacion->codoptsigla       = $sigla;
				$coddocumprocesocitacion->codoptanio        = $anioActual;
			}

			$coddocumprocesocitacion->tipactid               = $request->tipoCitacion;
			$coddocumprocesocitacion->codopthora             = $request->horaInicial;
		   	$coddocumprocesocitacion->codoptlugar            = $request->lugar;
		   	$coddocumprocesocitacion->codoptfecharealizacion = $fechaHoraActual;
		   	$coddocumprocesocitacion->save();
		
			foreach($request->firmaPersonas as $firmaPersona){
				$identificadorFirma = $firmaPersona['identificador'];
				$personaFirma       = $firmaPersona['persona'];
				$personaCargo       = $firmaPersona['cargo'];
				$personaEstado      = $firmaPersona['estado'];
				if($personaEstado === 'I'){
					$coddocumprocesofirma = new CodigoDocumentalProcesoFirma();
					$coddocumprocesofirma->codoprid  = $codoprid;
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}else if($personaEstado === 'D'){
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->delete();
				}else{
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}
			}

			if($request->firmaInvitados !== null){
				foreach($request->firmaInvitados as $firmaInvitado){
					$identificadorFirma = $firmaInvitado['identificador'];
					$personaFirma       = $firmaInvitado['persona'];
					$personaCargo       = $firmaInvitado['cargo'];
					$personaEstado      = $firmaInvitado['estado'];
					if($personaEstado === 'I'){
						$coddocumprocesofirma = new CodigoDocumentalProcesoFirma();
						$coddocumprocesofirma->codoprid         = $codoprid;
						$coddocumprocesofirma->persid           = $personaFirma;
						$coddocumprocesofirma->carlabid         = $personaCargo;
						$coddocumprocesofirma->codopfesinvitado = true;
						$coddocumprocesofirma->save();
					}else if($personaEstado === 'D'){
						$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
						$coddocumprocesofirma->delete();
					}else{
						$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
						$coddocumprocesofirma->persid    = $personaFirma;
						$coddocumprocesofirma->carlabid  = $personaCargo;
						$coddocumprocesofirma->save();
					}
				}
			}

			if($request->tipo === 'I'){
				//Almaceno la trazabilidad del documento
				$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
				$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
				$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
				$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
				$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
				$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->usuanombre;
				$codigodocumentalprocesocambioestado->save(); 
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

	public function constancia($request){

        $coddocid      				   = $request->idCD;
	    $codoprid      				   = $request->idCDP;
	    $codopnid      				   = $request->idCDPC;
        $codigodocumental              = ($coddocid != 000) ? CodigoDocumental::findOrFail($coddocid) : new CodigoDocumental();
		$codigodocumentalproceso       = ($codoprid != 000) ? CodigoDocumentalProceso::findOrFail($codoprid) : new CodigoDocumentalProceso();
		$coddocumprocesoconstancia     = ($codopnid != 000) ? CodigoDocumentalProcesoConstancia::findOrFail($codopnid) : new CodigoDocumentalProcesoConstancia();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid', $request->dependencia)->first();
			$sigla          = $dependencia->depesigla;

			if($request->tipo === 'I'){
				$codigodocumental->depeid          = $request->dependencia;
				$codigodocumental->serdocid        = $request->serie;
				$codigodocumental->susedoid        = $request->subSerie;
				$codigodocumental->tipdocid        = '5';//Constancia
				$codigodocumental->tiptraid        = $request->tipoTramite;
				$codigodocumental->usuaid          = $usuarioId;
				$codigodocumental->coddocfechahora = $fechaHoraActual;
			}
			$codigodocumental->tipmedid            = $request->tipoMedio;
			$codigodocumental->tipdetid            = $request->tipoDestino;
		   	$codigodocumental->save();

			if($request->tipo === 'I'){
				//Consulto el ultimo identificador de los codigos documentales
				$codDocMaxConsecutio               = CodigoDocumental::latest('coddocid')->first();
				$coddocid                          = $codDocMaxConsecutio->coddocid;
				$codigodocumentalproceso->coddocid = $coddocid;
	    		$codigodocumentalproceso->tiesdoid = '1'; //Inicial
			}
	    	
	    	$codigodocumentalproceso->codoprfecha            = $request->fecha;
	    	$codigodocumentalproceso->codoprnombredirigido   = $request->nombreDirigido;
	    	$codigodocumentalproceso->codoprcorreo           = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido        = $request->contenido;
	    	$codigodocumentalproceso->save();  

			if($request->tipo === 'I'){
				$codDocProcesoMaxConsecutio 				  = CodigoDocumentalProceso::latest('codoprid')->first();
				$codoprid                   				  = $codDocProcesoMaxConsecutio->codoprid;
				$coddocumprocesoconstancia->codoprid          = $codoprid;
				$coddocumprocesoconstancia->usuaid            = $usuarioId;
				$coddocumprocesoconstancia->codopnconsecutivo = $this->obtenerConsecutivo($sigla, $anioActual, 'T');
				$coddocumprocesoconstancia->codopnsigla       = $sigla;
				$coddocumprocesoconstancia->codopnanio        = $anioActual;
			}

			$coddocumprocesoconstancia->tipedoid                = $request->tipoPersona;
		   	$coddocumprocesoconstancia->codopntitulo            = $request->tituloDocumento;
		   	$coddocumprocesoconstancia->codopncontenidoinicial  = $request->contenidoInicial;
		   	$coddocumprocesoconstancia->save();

			foreach($request->firmaPersona as $firmaPersona){
				$identificadorFirma = $firmaPersona['identificador'];
				$personaFirma       = $firmaPersona['persona'];
				$personaCargo       = $firmaPersona['cargo'];
				$personaEstado      = $firmaPersona['estado'];
				if($personaEstado === 'I'){
					$coddocumprocesofirma = new CodigoDocumentalProcesoFirma();
					$coddocumprocesofirma->codoprid  = $codoprid;
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}else if($personaEstado === 'D'){
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->delete();
				}else{
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}
			}

			if($request->tipo === 'I'){
				//Almaceno la trazabilidad del documento
				$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
				$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
				$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
				$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
				$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
				$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->usuanombre;
				$codigodocumentalprocesocambioestado->save(); 
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function oficio($request){

        $coddocid      				   = $request->idCD;
	    $codoprid      				   = $request->idCDP;
	    $codopoid      				   = $request->idCDPO;
        $codigodocumental              = ($coddocid != 000) ? CodigoDocumental::findOrFail($coddocid) : new CodigoDocumental();
		$codigodocumentalproceso       = ($codoprid != 000) ? CodigoDocumentalProceso::findOrFail($codoprid) : new CodigoDocumentalProceso();
		$codigodocumentalprocesooficio = ($codopoid != 000) ? CodigoDocumentalProcesoOficio::findOrFail($codopoid) : new CodigoDocumentalProcesoOficio();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid', $request->dependencia)->first();
			$sigla          = $dependencia->depesigla;
			
			if($request->tipo === 'I'){
				$codigodocumental->depeid          = $request->dependencia;
				$codigodocumental->serdocid        = $request->serie;
				$codigodocumental->susedoid        = $request->subSerie;
				$codigodocumental->tipdocid        = '6';//Oficio
				$codigodocumental->tiptraid        = $request->tipoTramite;
				$codigodocumental->usuaid          = $usuarioId;
				$codigodocumental->coddocfechahora = $fechaHoraActual;
			}
			$codigodocumental->tipmedid            = $request->tipoMedio;
			$codigodocumental->tipdetid            = $request->tipoDestino;
		   	$codigodocumental->save();

			if($request->tipo === 'I'){
				//Consulto el ultimo identificador de los codigos documentales
				$codDocMaxConsecutio               = CodigoDocumental::latest('coddocid')->first();
				$coddocid                          = $codDocMaxConsecutio->coddocid;
				$codigodocumentalproceso->coddocid = $coddocid;
	    		$codigodocumentalproceso->tiesdoid = '1'; //Inicial
			}
	    	
	    	$codigodocumentalproceso->codoprfecha               = $request->fecha;
	    	$codigodocumentalproceso->codoprnombredirigido      = $request->nombreDirigido;
	    	$codigodocumentalproceso->codoprcargonombredirigido = $request->cargoDirigido;
	      	$codigodocumentalproceso->codoprasunto              = $request->asunto;
	    	$codigodocumentalproceso->codoprcorreo              = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido           = $request->contenido;
	    	$codigodocumentalproceso->codoprtieneanexo          = $request->tieneAnexo;
	    	$codigodocumentalproceso->codopranexonombre         = $request->nombreAnexo;
	    	$codigodocumentalproceso->codoprtienecopia          = $request->tieneCopia;
	    	$codigodocumentalproceso->codoprcopianombre         = $request->nombreCopia;
	    	$codigodocumentalproceso->save();  

			if($request->tipo === 'I'){
				$codDocProcesoMaxConsecutio 					  = CodigoDocumentalProceso::latest('codoprid')->first();
				$codoprid                   					  = $codDocProcesoMaxConsecutio->codoprid;
				$codigodocumentalprocesooficio->codoprid          = $codoprid;
				$codigodocumentalprocesooficio->usuaid            = $usuarioId;
				$codigodocumentalprocesooficio->codopoconsecutivo = $this->obtenerConsecutivo($sigla, $anioActual, 'O');
				$codigodocumentalprocesooficio->codoposigla       = $sigla;
				$codigodocumentalprocesooficio->codopoanio        = $anioActual;
			}

			$codigodocumentalprocesooficio->tipsalid                = $request->saludo;
			$codigodocumentalprocesooficio->tipdesid                = $request->despedida;
		   	$codigodocumentalprocesooficio->codopotitulo            = $request->tituloPersona;
		   	$codigodocumentalprocesooficio->codopociudad            = $request->ciudad;
		   	$codigodocumentalprocesooficio->codopocargodestinatario = $request->cargoDestinatario;
		   	$codigodocumentalprocesooficio->codopoempresa           = $request->empresa;
		   	$codigodocumentalprocesooficio->codopodireccion         = $request->direccionDestinatario;
			$codigodocumentalprocesooficio->codopotelefono          = $request->telefono;
			$codigodocumentalprocesooficio->codoporesponderadicado  = $request->responderRadicado;
		   	$codigodocumentalprocesooficio->save();	

			//Registramos los adjuntos
			if($request->hasFile('archivos')){
				$numeroAleatorio = rand(100, 1000);
				$funcion         = new generales();
				$rutaCarpeta     = public_path().'/archivos/produccionDocumental/adjuntos/'.$sigla.'/'.$anioActual;
				$carpetaServe    = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
				$files           = $request->file('archivos');
				foreach($files as $file){
					$nombreOriginal = $file->getclientOriginalName();
					$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
					$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo  = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
					$file->move($rutaCarpeta, $nombreArchivo);
					$rutaArchivo = Crypt::encrypt($nombreArchivo);

					$coddocumprocesoanexo = new CodigoDocumentalProcesoAnexo();
					$coddocumprocesoanexo->codoprid                  = $codoprid;
					$coddocumprocesoanexo->codopxnombreanexooriginal = $nombreOriginal;
					$coddocumprocesoanexo->codopxnombreanexoeditado  = $nombreArchivo;
					$coddocumprocesoanexo->codopxrutaanexo           = $rutaArchivo;
					$coddocumprocesoanexo->save();
				}
			}
		
			foreach($request->firmaPersonas as $firmaPersona){
				$identificadorFirma = $firmaPersona['identificador'];
				$personaFirma       = $firmaPersona['persona'];
				$personaCargo       = $firmaPersona['cargo'];
				$personaEstado      = $firmaPersona['estado'];
				if($personaEstado === 'I'){
					$coddocumprocesofirma = new CodigoDocumentalProcesoFirma();
					$coddocumprocesofirma->codoprid  = $codoprid;
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}else if($personaEstado === 'D'){
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->delete();
				}else{
					$coddocumprocesofirma = CodigoDocumentalProcesoFirma::findOrFail($identificadorFirma);
					$coddocumprocesofirma->persid    = $personaFirma;
					$coddocumprocesofirma->carlabid  = $personaCargo;
					$coddocumprocesofirma->save();
				}
			}

			if($request->tipo === 'U'){
				//Elimino las dependencia que esten en el documento
				$coddocumprocesocopiaConsultas = DB::table('coddocumprocesocopia')->select('codoppid')->where('codoprid', $codoprid)->get();
				foreach($coddocumprocesocopiaConsultas as $coddocumprocesocop){
					$coddocumprocesocopiaDelete = CodigoDocumentalProcesoCopia::findOrFail($coddocumprocesocop->codoppid);
					$coddocumprocesocopiaDelete->delete();
				}
			}

			if($request->copiasDependencia !== null){
				foreach($request->copiasDependencia as $copiaDependencia){
					$coddocumprocesocopia                         = new CodigoDocumentalProcesoCopia();
					$coddocumprocesocopia->codoprid               = $codoprid;
					$coddocumprocesocopia->depeid                 = $copiaDependencia['depeid'];
					$coddocumprocesocopia->codoppescopiadocumento = true;
					$coddocumprocesocopia->save();
				}
			}

			if($request->tipo === 'I'){
				//Almaceno la trazabilidad del documento
				$codigodocumentalprocesocambioestado 					= new CodigoDocumentalProcesoCambioEstado();
				$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
				$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
				$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
				$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
				$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->usuanombre;
				$codigodocumentalprocesocambioestado->save(); 
			}

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}    
    
	public function consultarAreaTrabajo()
	{
		return DB::table('dependencia as d')
						->select('d.depeid','d.depenombre','d.depesigla')
						->join('dependenciapersona as dp', 'dp.depperdepeid', '=', 'd.depeid')
						->where('dp.depperpersid', auth()->user()->persid)
						->orderBy('d.depenombre')->get();
	}
	 
	public function obtenerConsecutivo($sigla, $anioActual, $tipoDocumental)
	{
		if($tipoDocumental === 'A'){ //Acta
			$consecutivoTpDoc = DB::table('coddocumprocesoacta')->select('codopaconsecutivo as consecutivo')
								->where('codopaanio', $anioActual)->where('codopasigla', $sigla)->orderBy('codopaid', 'desc')->first();
		}else if($tipoDocumental === 'B'){//Certificado
			$consecutivoTpDoc = DB::table('coddocumprocesocertificado')->select('codopcconsecutivo as consecutivo')
								->where('codopcanio', $anioActual)->where('codopcsigla', $sigla)->orderBy('codopcid', 'desc')->first();
		}else if($tipoDocumental === 'C'){//Circular
			$consecutivoTpDoc = DB::table('coddocumprocesocircular')->select('codoplconsecutivo as consecutivo')
								->where('codoplanio', $anioActual)->where('codoplsigla', $sigla)->orderBy('codoplid', 'desc')->first();
		}else if($tipoDocumental === 'H'){//Citación
			$consecutivoTpDoc = DB::table('coddocumprocesocitacion')->select('codoptconsecutivo as consecutivo')
								->where('codoptanio', $anioActual)->where('codoptsigla', $sigla)->orderBy('codoptid', 'desc')->first();
		}else if($tipoDocumental === 'T'){//Constancia
			$consecutivoTpDoc = DB::table('coddocumprocesoconstancia')->select('codopnconsecutivo as consecutivo')
								->where('codopnanio', $anioActual)->where('codopnsigla', $sigla)->orderBy('codopnid', 'desc')->first();
		}else { //Oficio
			$consecutivoTpDoc = DB::table('coddocumprocesooficio')->select('codopoconsecutivo as consecutivo')
								->where('codopoanio', $anioActual)->where('codoposigla', $sigla)->orderBy('codopoid', 'desc')->first();
		}
		
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->consecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}
}