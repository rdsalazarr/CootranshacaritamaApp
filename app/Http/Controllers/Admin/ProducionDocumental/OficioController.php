<?php

namespace App\Http\Controllers\Admin\ProducionDocumental;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\CodigoDocumentalProcesoCambioEstado;
use App\Models\CodigoDocumentalProcesoCompartido;
use App\Models\CodigoDocumentalProcesoOficio;
use App\Models\CodigoDocumentalProcesoFirma;
use App\Models\CodigoDocumentalProcesoAnexo;
use App\Models\CodigoDocumentalProcesoCopia;
use App\Models\CodigoDocumentalProceso;
use App\Http\Requests\OficioRequests;
use App\Models\CodigoDocumental;
use App\Util\showTipoDocumental;
use App\Util\generales;
use Carbon\Carbon;
use Auth, DB;

//use App\Models\CambiarEstadoProducionDocumental;
//use App\Models\CompartirDocumento;
//use App\FuncionesGenerales;
//use App\ImprimirDocumentos;

class OficioController extends Controller
{
    public function index()
	{
	    /*$data = DB::table('coddocumprocesooficio as cdpo')
	    					->select('cd.coddocid','cdpo.codopoconsecutivo as consecutivo','cdpo.codoposigla as sigla', 
                                    'cdpo.codopoanio as anio','cdp.codoprid','cdp.codoprasunto','cdp.codoprfecha as fecha',
                                    'cdp.codoprnombredirigido', 'cdp.tiesdoid','ted.tiesdonombre','tt.tiptranombre','d.depenombre')
	  						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
	  						->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
	  						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
	  						->join('tipotramite as tt', 'tt.tiptraid', '=', 'cd.tiptraid')
	  						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
	  						->whereIn('cd.depeid', function($query){
						                $query->from('dependencia as d')
							                    ->join('dependenciausuario as du', 'du.depusudepeid', '=', 'd.depeid')
								  				->select('d.depeid')
								  				->where('du.depusuuserid', Auth::id());
						            })
	  						->orderBy('cdp.codoprfecha','Desc')->get();*/


		$data = DB::table('coddocumprocesooficio as cdpo')
						->select('cdpo.codopoid as id',DB::raw("CONCAT(cdpo.codopoanio,' - ', cdpo.codopoconsecutivo) as consecutivo"),
								'cdp.codoprfecha as fecha',  'cdp.codoprasunto as asunto','cdp.codoprnombredirigido as nombredirigido', 
								'd.depenombre as dependencia', 'ted.tiesdonombre as estado')
						->join('codigodocumentalproceso as cdp', 'cdp.codoprid', '=', 'cdpo.codoprid')
	  					->join('codigodocumental as cd', 'cd.coddocid', '=', 'cdp.coddocid')
						->join('tipoestadodocumento as ted', 'ted.tiesdoid', '=', 'cdp.tiesdoid')
						->join('dependencia as d', 'd.depeid', '=', 'cd.depeid')
						->orderBy('cdp.codoprfecha','Desc')->get();

        return response()->json(["data" => $data]);
	}

	public function datos(Request $request)
	{ 
		$id              = $request->id;
		$tipo            = $request->tipo;
		$data            = '';
		if($tipo === 'U'){
			$visualizar  = new showTipoDocumental();
			list($data, $firmasDocumento, $copiaDependencias) = $visualizar->oficio($id);
		}

		//Y-m-d m/d/Y
		$fechaActual     = Carbon::now()->format('Y-m-d');	
		$tipoDestinos    = DB::table('tipodestino')->select('tipdetid','tipdetnombre')->orderBy('tipdetnombre')->get();
		$tipoMedios      = DB::table('tipomedio')->select('tipmedid','tipmednombre')->orderBy('tipmednombre')->get();
		$tipoSaludos     = DB::table('tiposaludo')->select('tipsalid','tipsalnombre')->orderBy('tipsalnombre')->get();
        $tipoDespedidas  = DB::table('tipodespedida')->select('tipdesid','tipdesnombre')->orderBy('tipdesnombre')->get();
        $dependencias    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeactiva', true)->orderBy('depenombre')->get();
 		$personas        = DB::table('persona')->select('persid',DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre),' ', persprimerapellido,' ',if(perssegundoapellido is null ,' ', perssegundoapellido)) as nombrePersona"))
														->orderBy('nombrePersona')
														->whereIn('carlabid', [1, 2])->get();
        $cargoLaborales  = DB::table('cargolaboral')->select('carlabid','carlabnombre')->orderBy('carlabnombre')->whereIn('carlabid', [1, 2])->get();

        return response()->json(["fechaActual"    => $fechaActual,    "tipoDestinos"      => $tipoDestinos,     "tipoMedios"   => $tipoMedios,
                                "tipoSaludos"     => $tipoSaludos,     "tipoDespedidas"   => $tipoDespedidas,   "dependencias" => $dependencias,
								"personas"        => $personas,        "cargoLaborales"    => $cargoLaborales,  "data" => $data,
								"firmasDocumento" => $firmasDocumento, "copiaDependencias" => $copiaDependencias  ]);
	}

    public function salve(OficioRequests $request){

		$codigodocumental                    = new CodigoDocumental();
		$codigodocumentalproceso             = new CodigoDocumentalProceso();
		$codigodocumentalprocesooficio       = new CodigoDocumentalProcesoOficio();
		//$codigodocumentalprocesofirma        = new CodigoDocumentalProcesoFirma();
		$codigodocumentalprocesocambioestado = new CodigoDocumentalProcesoCambioEstado();
       	
       // $id      = $request->codigo;
        //$infocorreonotificacion = ($id != 000) ? InformacionNotificacionCorreo::findOrFail($id) : new InformacionNotificacionCorreo();


        DB::beginTransaction();
		try {
            $usuarioId       = Auth::id();
            $fechaHoraActual = Carbon::now();
            $anioActual      = Carbon::now()->year;

			//Consulto la sigla
			$dependencia    = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeid',$request->dependencia)->first();
			$sigla          = $dependencia->depesigla;

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
	
			//Registramos los adjuntos
			$numeroAleatorio = rand(100, 1000);
			$funcion         = new generales();
			$rutaCarpeta     = public_path().'/archivos/produccionDocumental/adjuntos/'.$sigla.'/'.$anioActual;
			$carpetaServe    = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true);
	
			for($i = 0; $i < $request->totalCamposArchivos; $i++){
				$archivos   = 'archivos'.$i;
				if($request->hasFile($archivos)){
					$file = $request->file($archivos);
					$nombreOriginal = $file->getclientOriginalName();
					$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
					$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
					$nombreArchivo  = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
					$file->move($rutaCarpeta, $nombreArchivo);
					$rutaArchivo = Crypt::encrypt($nombreArchivo);
				}
				$coddocumprocesoanexo = new CodigoDocumentalProcesoAnexo();
				$coddocumprocesoanexo->codoprid                  = $codoprid;
				$coddocumprocesoanexo->codopxnombreanexooriginal = $nombreOriginal;
				$coddocumprocesoanexo->codopxnombreanexoeditado  = $nombreArchivo;
				$coddocumprocesoanexo->codopxrutaanexo           = $rutaArchivo;
				$coddocumprocesoanexo->save();
			}

			for($i = 0; $i < $request->totalCamposFirmas; $i++){
				$identificadorFirma = 'identificadorFirma'.$i;
				$personaFirma       = 'personaFirma'.$i;
				$personaCargo       = 'personaCargo'.$i;
				$personaEstado      = 'personaEstado'.$i;

				$coddocumprocesoanexo = new CodigoDocumentalProcesoFirma();
				$coddocumprocesoanexo->codoprid  = $codoprid;
				$coddocumprocesoanexo->persid    = $request->$personaFirma;
				$coddocumprocesoanexo->carlabid  = $request->$personaCargo;
				$coddocumprocesoanexo->save();
			}

			//Almaceno la trazabilidad del documento
			$codigodocumentalprocesocambioestado->codoprid          = $codigodocumentalproceso->codoprid;
			$codigodocumentalprocesocambioestado->tiesdoid          = '1';//Inicial
			$codigodocumentalprocesocambioestado->codpceuserid      = $usuarioId;
			$codigodocumentalprocesocambioestado->codpcefechahora   = $fechaHoraActual;
			$codigodocumentalprocesocambioestado->codpceobservacion = 'Creación del documento por '.auth()->user()->usuanombre;
			$codigodocumentalprocesocambioestado->save(); 
			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    //Funcion que permite obtener el consecutivo del documento
	public function obtenerConsecutivo($sigla, $anioActual)
	{
		$consecutivoTpDoc = DB::table('coddocumprocesooficio')->select('codopoconsecutivo')
								->where('.codopoanio', $anioActual)->where('codoposigla', $sigla)
								->orderBy('codopoid', 'desc')->first();
        $consecutivo = ($consecutivoTpDoc === null) ? 1 : $consecutivoTpDoc->codopoconsecutivo + 1;

		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}
}