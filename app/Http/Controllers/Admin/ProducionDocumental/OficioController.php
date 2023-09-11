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

	public function datos(Request $request)
	{ 
		$id              = $request->codigo;
		//Y-m-d m/d/Y
		$fechaActual     = Carbon::now()->format('Y-m-d');	
		$tipoDestinos    = DB::table('tipodestino')->select('tipdetid','tipdetnombre')->orderBy('tipdetnombre')->get();
		$tipoMedios      = DB::table('tipomedio')->select('tipmedid','tipmednombre')->orderBy('tipmednombre')->get();
		$tipoSaludos     = DB::table('tiposaludo')->select('tipsalid','tipsalnombre')->orderBy('tipsalnombre')->get();
        $tipoDespedidas  = DB::table('tipodespedida')->select('tipdesid','tipdesnombre')->orderBy('tipdesnombre')->get();
        $dependencias    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeactiva', true)->orderBy('depenombre')->get();
 
        return response()->json(["fechaActual" => $fechaActual, "tipoDestinos"   => $tipoDestinos,   "tipoMedios"   => $tipoMedios,
                                 "tipoSaludos" => $tipoSaludos, "tipoDespedidas" => $tipoDespedidas, "dependencias" => $dependencias ]);
	}

    public function salve(OficioRequests $request){

		$codigodocumental                    = new CodigoDocumental();
		$codigodocumentalproceso             = new CodigoDocumentalProceso();
		$codigodocumentalprocesooficio       = new CodigoDocumentalProcesoOficio();
		$codigodocumentalprocesofirma        = new CodigoDocumentalProcesoFirma();
		$codigodocumentalprocesocambioestado = new CodigoDocumentalProcesoCambioEstado();
       	
       // $id      = $request->codigo;
        //$infocorreonotificacion = ($id != 000) ? InformacionNotificacionCorreo::findOrFail($id) : new InformacionNotificacionCorreo();
		
		dd($request->fecha);

        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid',$request->dependencia)->first();
			$sigla = $dependencia->depesigla;

	    	$codigodocumental->depeid          = $request->dependencia;
		    $codigodocumental->serdocid        = $request->serie;
		    $codigodocumental->susedoid        = $request->subSerie;	
		    $codigodocumental->tipdocid        = '6';//Oficio
		    $codigodocumental->tipmedid        = $request->tipoMedio;
		    $codigodocumental->tiptraid        = $request->tipoTramite;
		    $codigodocumental->tipdetid        = $request->tipoDestino;
		    $codigodocumental->usuaid          = $usuarioId;
            $codigodocumental->coddocfechahora = $fechaHoraActual;	  
		   	$codigodocumental->save();

		   	//Consulto el ultimo identificador de los codigos documentales
            $codDocMaxConsecutio = CodigoDocumental::latest('coddocid')->first();
            $coddocid            =  $codDocMaxConsecutio->coddocid;

	    	$codigodocumentalproceso->coddocid                  =  $coddocid;
	    	$codigodocumentalproceso->tiesdoid                  = '1'; //Inicial
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

            $codDocProcesoMaxConsecutio = CodigoDocumentalProceso::latest('codoprid')->first();
            $codoprid                   =  $codDocProcesoMaxConsecutio->codoprid;

		   	$codigodocumentalprocesooficio->codoprid                = $codoprid;
			$codigodocumentalprocesooficio->usuaid                  = $usuarioId;
			$codigodocumentalprocesooficio->tipsalid                = $request->saludo;
			$codigodocumentalprocesooficio->tipdesid                = $request->despedida;
			$codigodocumentalprocesooficio->codopoconsecutivo       = $this->obtenerConsecutivo($sigla, $anioActual);
		   	$codigodocumentalprocesooficio->codoposigla             = $sigla;
		   	$codigodocumentalprocesooficio->codopoanio              = $anioActual;
		  
		   	$codigodocumentalprocesooficio->codopotitulo            = $request->tituloPersona;
		   	$codigodocumentalprocesooficio->codopociudad            = $request->ciudad;
		   	$codigodocumentalprocesooficio->codopocargodestinatario = $request->cargoDestinatario;
		   	$codigodocumentalprocesooficio->codopoempresa           = $request->empresa;
		   	$codigodocumentalprocesooficio->codopodireccion         = $request->direccionDestinatario;
			$codigodocumentalprocesooficio->codopotelefono          = $request->telefono;
			$codigodocumentalprocesooficio->codoporesponderadicado  = $request->responderRadicado;
		   	$codigodocumentalprocesooficio->save();

			//Elimino las funcionalides asignada
			/*if($request->tipo === 'U'){
				$rolfuncionalidad = DB::table('rolfuncionalidad')->select('rolfunid')
						->where('rolfunrolid', $request->codigo)->get();
				foreach ($rolfuncionalidad as $funcionalidad)
            	{
					$rolfuncionalidad = CodigoDocumentalProcesoCopia::findOrFail($funcionalidad->rolfunid);
					$rolfuncionalidad->delete();
				}
			}*/

			/*foreach ($request->dependenciaCopias as $dependenciaCopia)
            {
                $codigodocumentalprocesofirma= new CodigoDocumentalProcesoFirma();
                $codigodocumentalprocesofirma->codoprid = $idRol;
                $codigodocumentalprocesofirma->persid = $dependenciaCopia['funcid'];
                $codigodocumentalprocesofirma->save();
            }



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
