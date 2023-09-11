<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoCompartido;
use App\Models\CodigoDocumentalProcesoOficio;
use App\Models\CodigoDocumentalProcesoFirma;
use App\Models\CodigoDocumentalProcesoAnexo;
use App\Models\CodigoDocumentalProcesoCopia;
use App\Models\CodigoDocumentalProceso;
use App\Http\Requests\OficioRequests;
use App\Models\CodigoDocumental;
use App\Util\generales;
use Carbon\Carbon;
use DB;

//use App\Models\CambiarEstadoProducionDocumental;
//use App\Models\CompartirDocumento;
//use App\FuncionesGenerales;
//use App\ImprimirDocumentos;

class OficioController extends Controller
{
    public function index()
	{
	    $data = DB::table('coddocumprocesooficio as cdpo')
	    					->select('cd.coddocid','cdpo.codopoconsecutivo as consecutivo','cdpo.codoposigla as sigla', 
                                    'cdpo.codopoanio as anio','cdp.codoprid','cdp.codoprasunto','cdp.codoprfecha as fecha',
                                    'cdp.codoprnombredirigido', 'cdp.tiesdoid','ted.tiesdonombre','tt.tiptranombre','d.depenombre')
	  						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
	  						->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
	  						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
	  						->join('tipotramite as tt', 'tt.tiptraid', '=', 'cd.tiptraid')
	  						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
	  						/*->whereIn('cd.depeid', function($query){
						                $query->from('dependencia as d')
							                    ->join('dependenciausuario as du', 'du.depusudepeid', '=', 'd.depeid')
								  				->select('d.depeid')
								  				->where('du.depusuuserid', Auth::id());
						            })*/
	  						->orderBy('cdp.codoprfecha','Desc')->get();

        return response()->json(["data" => $data]);
	}

    public function salve(OficioRequests $request){ 

		$codigodocumental                    = new CodigoDocumental();		
		$codigodocumentalproceso             = new CodigoDocumentalProceso();
		$codigodocumentalprocesooficio       = new CodigoDocumentalProcesoOficio();
		$codigodocumentalprocesofirma        = new CodigoDocumentalProcesoFirma();
		$codigodocumentalprocesocambioestado = new CodigoDocumentalProcesoCambioEstado();	
       	
       // $id      = $request->codigo;
        //$infocorreonotificacion = ($id != 000) ? InformacionNotificacionCorreo::findOrFail($id) : new InformacionNotificacionCorreo();

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

	    	$codigodocumental->depeid          = $request->dependenciaproductora;
		    $codigodocumental->seriid          =  $request->serie;
		    $codigodocumental->subserid        = $request->subSerie;	
		    $codigodocumental->tipdocid        = '6';//Oficio
		    $codigodocumental->tipmedid        = $request->tipoMedio;
		    $codigodocumental->tiptraid        = $request->tipoTramite;
		    $codigodocumental->tipdetid        = $request->tipoDestino;
		    $codigodocumental->coddocuserid    = $usuarioId;
            $codigodocumental->coddocfechahora = $fechaHoraActual;	  
		   	$codigodocumental->save();  

		   	//Consulto el ultimo identificador de los codigos documentales
            $codDocMaxConsecutio = CodigoDocumental::latest('coddocid')->first();
            $coddocid =  $codDocMaxConsecutio->coddocid;

	    	$codigodocumentalproceso->coddocid                  =  $coddocid;
	    	$codigodocumentalproceso->tiesdoid                  = '1'; //Inicial
	    	$codigodocumentalproceso->codoprfecha               = $request->fecha;
	    	$codigodocumentalproceso->codoprnombredirigido      = $request->destinatario;
	    	$codigodocumentalproceso->codoprcargonombredirigido = $request->cargo;
	      	$codigodocumentalproceso->codoprasunto              = $request->asunto;
	    	$codigodocumentalproceso->codoprcorreo              = $request->correo;
	    	$codigodocumentalproceso->codoprcontenido           = $request->contenido;
	    	$codigodocumentalproceso->codoprtieneanexo          = $request->anexarDocumento;
	    	$codigodocumentalproceso->codopranexonombre         = $request->nombreAnexo;
	    	$codigodocumentalproceso->codoprtienecopia          = $request->enviarCopia;
	    	$codigodocumentalproceso->codoprcopianombre         = $request->copiaNombre;	    		 
	    	$codigodocumentalproceso->save();  

            $codDocProcesoMaxConsecutio = CodigoDocumentalProceso::latest('codoprid')->first();
            $codoprid =  $codDocProcesoMaxConsecutio->codoprid;

		   	$codigodocumentalprocesooficio->codoprid                = $codoprid;		   
			$codigodocumentalprocesooficio->codopouserid            = $usuarioId;
			$codigodocumentalprocesooficio->codopoconsecutivo       = $this->obtenerConsecutivo($request->dependenciaproductora, $anioActual);
		   	$codigodocumentalprocesooficio->codoposigla             = $request->depeproductora;
		   	$codigodocumentalprocesooficio->codopoanio              = $anioActual;
		   	$codigodocumentalprocesooficio->tipsalid                = $request->saludo;
		   	$codigodocumentalprocesooficio->tipdesid                = $request->despedida;
		   	$codigodocumentalprocesooficio->codopotitulo            = $request->titulo;
		   	$codigodocumentalprocesooficio->codopociudad            = $request->ciudad;
		   	$codigodocumentalprocesooficio->codopocargodestinatario = $request->cargo;
		   	$codigodocumentalprocesooficio->codopoempresa           = $request->empresa;
		   	$codigodocumentalprocesooficio->codopodireccion         = $request->direccion;
			$codigodocumentalprocesooficio->codopotelefono          = $request->telefono;
			$codigodocumentalprocesooficio->codoporesponderadicado  = $request->responder_radicado;
		   	$codigodocumentalprocesooficio->save();  
		 /*
			//Obtenemos los vectores
		   	$estadoFirma = $request->estado_firma;	//Este estado solo se pasa porque se requiere al copiar el documento	   
		   	$cargoRemitente = $request->cargo_remitente;
		   	
		   	///Almaceno las firmas
		  	$i = 0;
			foreach ($request->nombre_remitente as $remitente)
			{	
				$estadoActual = $estadoFirma[$i];
				$cargo = $cargoRemitente[$i];				

				if($estadoActual == 'N'){//inserto registros		
					$codigodocumentalprocesofirma = new CodigoDocumentalProcesoFirma();	
					$codigodocumentalprocesofirma->codoprid = $codigodocumentalproceso->codoprid;
					$codigodocumentalprocesofirma->persid = $remitente;
					$codigodocumentalprocesofirma->carlabid = $cargo;
					$codigodocumentalprocesofirma->save(); 
				}
				$i += 1; //incrementa el contador
			}

	    	//Verifico si el documento tiene copias
	    	if($request->nombre_dependencia !=''){
	    		//Obtenemos los vectores
			   	$estadoDependencia = $request->estado_dependencia;
			   					   	
			   	///Almaceno las firmas
			  	$i = 0;
				foreach ($request->nombre_dependencia as $dependencia)
				{	
					$estadoActual = $estadoDependencia[$i];					

					if($estadoActual ==  'N'){//inserto registros al copiar puede venir un D			
						$codigodocumentalprocesocopia = new CodigoDocumentalProcesoCopia();	
						$codigodocumentalprocesocopia->codoprid = $codigodocumentalproceso->codoprid;
						$codigodocumentalprocesocopia->depeid = $dependencia;
						$codigodocumentalprocesocopia->codoppescopiadocumento = true;
						$codigodocumentalprocesocopia->save(); 
					}
					$i += 1; //incrementa el contador
				}
	    	}*/

		   	//Almaceno los documento adjuntos


			//Almaceno la trazabilidad del documento
			$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
			$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
			$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
			$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
			$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->name;
			$codigodocumentalprocesocambioestado->save(); 

			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    //Funcion que permite obtener el consecutivo del documento
	public function obtenerConsecutivo($depeid, $anioActual)
	{
		$consecutivo = DB::table('dependencia as d')
						->select('cdpo.codopoconsecutivo')
						->join('coddocumprocesooficio as cdpo', 'cdpo.codoposigla', '=', 'd.depesigla')
						->where('cdpo.codopoanio', $anioActual)
						->where('d.depeid', $depeid)
						->orderBy('cdpo.codopoid', 'desc')->first();

        $consecutivo = ($consecutivo === '') ? 1 : $consecutivo->codopoconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}


}
