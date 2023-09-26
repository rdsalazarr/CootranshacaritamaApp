<?php

namespace App\Util;

use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoCertificado;
use App\Models\CodigoDocumentalProcesoConstancia;
use App\Models\CodigoDocumentalProcesoCompartido;
use App\Models\CodigoDocumentalProcesoCircular;
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

class editarDocumentos {
   
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
				$codigodocumentalprocesoacta->codopaconsecutivo = $this->obtenerConsecutivoActa($sigla, $anioActual);
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
				$coddocumprocesocertificado->codopcconsecutivo = $this->obtenerConsecutivoCertificado($sigla, $anioActual);
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
				$coddocumprocesocircular->codoplconsecutivo = $this->obtenerConsecutivoCircular($sigla, $anioActual);
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
				$coddocumprocesocitacion->codoptconsecutivo = $this->obtenerConsecutivoCitacion($sigla, $anioActual);
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

	public function constanci($request){

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
				$coddocumprocesoconstancia->codopnconsecutivo = $this->obtenerConsecutivoConstancia($sigla, $anioActual);
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
				$codigodocumentalprocesooficio->codopoconsecutivo = $this->obtenerConsecutivoOficio($sigla, $anioActual);
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
 
	public function obtenerConsecutivoActa($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesoacta')->select('codopaconsecutivo')
								->where('codopaanio', $anioActual)->where('codopasigla', $sigla)
								->orderBy('codopaid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codopaconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}

	public function obtenerConsecutivoCertificado($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesocertificado')->select('codopcconsecutivo')
								->where('codopcanio', $anioActual)->where('codopcsigla', $sigla)
								->orderBy('codopcid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codopcconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}

    public function obtenerConsecutivoCircular($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesocircular')->select('codoplconsecutivo')
								->where('codoplanio', $anioActual)->where('codoplsigla', $sigla)
								->orderBy('codoplid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codoplconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}

	public function obtenerConsecutivoCitacion($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesocitacion')->select('codoptconsecutivo')
								->where('codoptanio', $anioActual)->where('codoptsigla', $sigla)
								->orderBy('codoptid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codoptconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}

	public function obtenerConsecutivoConstancia($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesoconstancia')->select('codopnconsecutivo')
								->where('codopnanio', $anioActual)->where('codopnsigla', $sigla)
								->orderBy('codopnid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codopnconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}

	public function obtenerConsecutivoOficio($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesooficio')->select('codopoconsecutivo')
								->where('codopoanio', $anioActual)->where('codoposigla', $sigla)
								->orderBy('codopoid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codopoconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}

}