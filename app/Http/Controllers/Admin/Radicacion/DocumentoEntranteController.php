<?php

namespace App\Http\Controllers\Admin\Radicacion;

use App\Models\RadicacionDocumentoEntranteCambioEstado;
use App\Models\RadicacionDocumentoEntranteAnexo;
use App\Models\RadicacionDocumentoEntrante;
use App\Models\PersonaRadicaDocumento;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\notificar;
use App\Util\generales;
use Auth, DB, File;
use Carbon\Carbon;

class DocumentoEntranteController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);
        $data = [];
        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
		/*$this->validate(request(),['tipo' => 'required']);	
		$id                = $request->id;
		$tipo              = $request->tipo;*/
        $data              = [];
        $copiaDependencias = [];
        $anexosRadicados   = [];


        $fechaActual           = Carbon::now()->format('Y-m-d');
        $tipoMedios            = DB::table('tipomedio')->select('tipmedid','tipmednombre')->whereIn('tipmedid', [1,2,3])->orderBy('tipmednombre')->get();
		$tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();
        $dependencias          = DB::table('dependencia')->select('depeid','depesigla','depenombre')->where('depeactiva', true)->orderBy('depenombre')->get();	
        $departamentos         = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get();
        $municipios            = DB::table('municipio')->select('muniid','munidepaid','muninombre')->orderBy('muninombre')->get();

        return response()->json(["fechaActual"    => $fechaActual,   "tipoMedios"        => $tipoMedios,       "tipoIdentificaciones" => $tipoIdentificaciones,
                                "dependencias"    => $dependencias,  "departamentos"     => $departamentos,    "municipios"           => $municipios,
								"data"            => $data,          "copiaDependencias" => $copiaDependencias, "anexosRadicados"     => $anexosRadicados ]);
	}

    public function salve(Request $request)
	{
	    $this->validate(request(),[
                'tipoIdentificacion'      => 'required|numeric',
                'numeroIdentificacion'    => 'required|string|max:15',                
                'primerNombre'            => 'required|string|min:4|max:70',
                'segundoNombre'           => 'nullable|string|min:4|max:40',
                'primerApellido'          => 'nullable|string|min:4|max:40',
                'segundoApellido'         => 'nullable|string|min:4|max:40',
                'direccionFisica'         => 'required|string|min:4|max:100',
                'correoElectronico'       => 'nullable|email|string|max:80',
                'numeroContacto'          => 'nullable|string|max:20',
                'codigoDocumental'        => 'nullable|string|max:20',

                'fechaLlegadaDocumento'   => 'required|date|date_format:Y-m-d',
                'fechaDocumento'          => 'required|date|date_format:Y-m-d',
                'dependencia'             => 'required|numeric',        
                'departamento'            => 'required|numeric',
                'municipio'               => 'required|numeric',
                'asuntoRadicado'          => 'required|string|min:4|max:500',
                'personaEntregaDocumento' => 'required|string|min:4|max:100',
                'tieneAnexos'             => 'nullable|numeric',
                'descripcionAnexos'       => 'nullable|string|min:4|max:300',
                'tieneCopia'              => 'nullable|numeric',
                'observacionGeneral'      => 'nullable|string|min:4|max:300',
                'personaId'               => 'nullable|numeric',
                //'archivos'             => 'nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG|max:1000'
	        ]);

        DB::beginTransaction();
        try {

            $estado              = '1'; //Recibido
            $fechaHoraActual     = Carbon::now();
            $anioActual          = Carbon::now()->year;
            $funcion 		     = new generales();
            /*$rutaCarpeta         = public_path().'/archivos/persona/'.$request->documento;
            $carpetaServe        = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            if($request->hasFile('firma')){
				$file = $request->file('firma');
				$nombreOriginal = $file->getclientOriginalName();
				$filename   = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension  = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaFirma  = 'Firma_'.$request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFirma);
			}else{
				$rutaFirma = $request->rutaFirma_old;
			}

            if($request->hasFile('fotografia')){
				$file = $request->file('fotografia');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaFotografia = $request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFotografia);
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaFotografia, 210, 270);//Se redimenciona a un solo tipo
			}else{
				$rutaFotografia = $request->rutaFoto_old;
			}*/

            $personaradicadocumento                         = new PersonaRadicaDocumento();
            $personaradicadocumento->tipideid               = $request->tipoIdentificacion;
            $personaradicadocumento->peradodocumento        = $request->numeroIdentificacion;
            $personaradicadocumento->peradoprimernombre     = $request->primerNombre;
            $personaradicadocumento->peradosegundonombre    = $request->segundoNombre;
            $personaradicadocumento->peradoprimerapellido   = $request->primerApellido;
            $personaradicadocumento->peradosegundoapellido  = $request->segundoApellido;
            $personaradicadocumento->peradodireccion        = $request->direccionFisica; 
            $personaradicadocumento->peradotelefono         = $request->numeroContacto; 
            $personaradicadocumento->peradocorreo           = $request->correoElectronico;
            $personaradicadocumento->peradocodigodocumental = $request->codigoDocumental;
            $personaradicadocumento->save();

            //Consulto el ultimo identificador de la persona 
            $perRadDocumentoMaxConsecutio  = PersonaRadicaDocumento::latest('peradoid')->first();
            $peradoid                      = $perRadDocumentoMaxConsecutio->peradoid;

            $radicaciondocumentoentrante                                = new RadicacionDocumentoEntrante();
            $radicaciondocumentoentrante->peradoid                      = $peradoid;
            $radicaciondocumentoentrante->tipmedid                      = $request->tipoMedio;
            $radicaciondocumentoentrante->tierdeid                      = $estado;
            $radicaciondocumentoentrante->depeid                        = $request->dependencia;
            $radicaciondocumentoentrante->usuaid                        = Auth::id();
            $radicaciondocumentoentrante->depaid                        = $request->departamento;
            $radicaciondocumentoentrante->muniid                        = $request->municipio;
            $radicaciondocumentoentrante->radoenconsecutivo             = $this->obtenerConsecutivo($anioActual);
            $radicaciondocumentoentrante->radoenanio                    = $anioActual;
            $radicaciondocumentoentrante->radoenfechahoraradicado       = $fechaHoraActual;
            $radicaciondocumentoentrante->radoenfechadocumento          = $request->fechaDocumento;
            $radicaciondocumentoentrante->radoenfechallegada            = $request->fechaLlegadaDocumento;
            $radicaciondocumentoentrante->radoenpersonaentregadocumento = $request->personaEntregaDocumento;
            $radicaciondocumentoentrante->radoenasunto                  = $request->asuntoRadicado;
            $radicaciondocumentoentrante->radoentieneanexo              = $request->tieneAnexos;
            $radicaciondocumentoentrante->radoendescripcionanexo        = $request->descripcionAnexos;
            $radicaciondocumentoentrante->radoentienecopia              = $request->tieneCopia;
            $radicaciondocumentoentrante->radoenobservacion             = $request->observacionGeneral;
            $radicaciondocumentoentrante->save();

            //Consulto el ultimo identificador de la persona 
            $radDocumentoMaxConsecutio  = RadicacionDocumentoEntrante::latest('radoenid')->first();
            $radoenid                   = $radDocumentoMaxConsecutio->radoenid;

            //Almaceno la trazabilidad del documento
			$radicaciondocentcambioestado 					 = new RadicacionDocumentoEntranteCambioEstado();
			$radicaciondocentcambioestado->radoenid          = $radoenid;
			$radicaciondocentcambioestado->tierdeid          = $estado;
			$radicaciondocentcambioestado->radeceusuaid      = Auth::id();
			$radicaciondocentcambioestado->radecefechahora   = $fechaHoraActual;
			$radicaciondocentcambioestado->radeceobservacion = 'Documento radicado por '.auth()->user()->usuanombre.'  en la fecha '.$fechaHoraActual;
			$radicaciondocentcambioestado->save();

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric', 'numeroIdentificacion' => 'required|string|max:15']);

        $data     = DB::table('personaradicadocumento as prd')
                            ->select('prd.peradoid','prd.tipideid','prd.peradodocumento','prd.peradoprimernombre','prd.peradosegundonombre',
                            'prd.peradoprimerapellido','prd.peradosegundoapellido', 'prd.peradodireccion','prd.peradotelefono','prd.peradocorreo',
                            'prd.peradocodigodocumental','rde.radoenpersonaentregadocumento')
                            ->join('radicaciondocumentoentrante as rde', 'rde.peradoid', '=', 'prd.peradoid')                            
                            ->where('prd.tipideid', $request->tipoIdentificacion)
                            ->where('prd.peradodocumento', $request->numeroIdentificacion)->first();

        return response()->json(['success' => ($data !== null) ? true : false, 'data' => $data]);
    }

    public function obtenerConsecutivo($anioActual)
	{
		$consecutivoRadicado = DB::table('radicaciondocumentoentrante')->select('radoenconsecutivo as consecutivo')
								->where('radoenanio', $anioActual)->orderBy('radoenid', 'desc')->first();
        $consecutivo = ($consecutivoRadicado === null) ? 1 : $consecutivoRadicado->consecutivo + 1;
		return str_pad( $consecutivo,  4, "0", STR_PAD_LEFT);
	}
}
