<?php

namespace App\Http\Controllers\Admin\Persona;

use App\Models\Conductor\ConductorLicencia;
use App\Http\Requests\PersonaRequests;
use App\Http\Controllers\Controller;
use App\Models\Conductor\Conductor;
use App\Models\Asociado\Asociado;
use App\Util\redimencionarImagen;
use App\Models\Persona\Persona;
use Illuminate\Http\Request;
use App\Util\personaManager;
use Exception, File, DB, URL;
use App\Util\generales;

class PersonaController extends Controller
{
    public function index()
    { 
        $data = DB::table('persona as p')->select('p.persid','p.persdocumento', 'p.persdireccion','p.perscorreoelectronico',
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as tipoIdentificacion"),
                                    DB::raw("if(p.persactiva = 1 ,'Sí', 'No') as estado"), 'tp.tippernombre as tipoPersona')
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
									->join('tipopersona as tp', 'tp.tipperid', '=', 'p.tipperid')
                                    ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                    ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();

        return response()->json(["data" => $data]);
    }

    public function salve(PersonaRequests $request)
	{
        $personaManager = new personaManager();
		return $personaManager->registrar($request);
	}
	
	public function procesar(Request $request)
	{ 
		$this->validate(request(),['tipo' 					 => 'required', 
									'codigo' 				 => 'required',
									'fechaIngresoAsociado'   => 'nullable|date_format:Y-m-d|required_if:tipo,ASOCIADO',
									'fechaIngresoConductor'  => 'nullable|date_format:Y-m-d|required_if:tipo,CONDUCTOR' ]);

		DB::beginTransaction();
		try {
			$codigo = $request->codigo;
			if($request->tipo === 'ASOCIADO' ){
				$asociado                   = new Asociado();
				$asociado->persid           = $codigo;
				$asociado->tiesasid         = 'A';
				$asociado->asocfechaingreso = $request->fechaIngresoAsociado;
				$asociado->save();
			}

			if($request->tipo === 'CONDUCTOR' ){

				$redimencionarImagen = new redimencionarImagen();
            	$funcion 		     = new generales();
				
				$documentoPersona    = $request->documento;
				$rutaCarpeta         = public_path().'/archivos/persona/'.$documentoPersona;
				$carpetaServe        = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 

				$debeActualizarImagen = false;
                if($request->hasFile('imagenLicencia')){
                    $debeActualizarImagen = true;
                    $numeroAleatorio      = rand(100, 1000);
                    $file                 = $request->file('imagenLicencia');
                    $nombreOriginalLic    = $file->getclientOriginalName();
                    $filename             = pathinfo($nombreOriginalLic, PATHINFO_FILENAME);
                    $extension            = pathinfo($nombreOriginalLic, PATHINFO_EXTENSION);
                    $rutaImagenLicencia   = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
                    $file->move($rutaCarpeta, $rutaImagenLicencia);
                    $rutaArchivo          = Crypt::encrypt($rutaImagenLicencia);
                    $extension            = mb_strtoupper($extension,'UTF-8');
                    if($extension !== 'PDF')
                        $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaImagenLicencia, 480, 340);//Se redimenciona a un solo tipo (ancho * alto)
                }

				$conductor                   = new Conductor();
				$conductor->persid           = $codigo;
				$conductor->tiescoid         = 'A';
				$conductor->tipconid         = $request->tipoConductor;
				$conductor->agenid           = $request->agencia;
				$conductor->condfechaingreso = $request->fechaIngresoConductor;
				$conductor->save();

				$personaMaxConductor        = Conductor::latest('condid')->first();
				$condid                     = $personaMaxConductor->condid;

				$conductorlicencia                              = new ConductorLicencia();
				$conductorlicencia->condid                      = $condid;
				$conductorlicencia->ticaliid                    = $request->tipoCategoria;
				$conductorlicencia->conlicnumero                = $request->numeroLicencia;
				$conductorlicencia->conlicfechaexpedicion       = $request->fechaExpedicion;
				$conductorlicencia->conlicfechavencimiento      = $request->fechaVencimiento;
				if($debeActualizarImagen){
					$conductorlicencia->conlicextension             = $extension;
					$conductorlicencia->conlicnombrearchivooriginal = $nombreOriginalLic;
					$conductorlicencia->conlicnombrearchivoeditado  = $rutaImagenLicencia;
					$conductorlicencia->conlicrutaarchivo           = $rutaArchivo;
				}
				$conductorlicencia->save();
            }

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$usuario              = DB::table('usuario')->select('persid')->where('persid', $request->codigo)->first();
        $coddocumprocesofirma = DB::table('coddocumprocesofirma')->select('persid')->where('persid', $request->codigo)->first();

		if($usuario || $dependenciapersona){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un usuario o a ha firmado un documento del sistema']);
		}else{
			try {
				$persona = Persona::findOrFail($request->codigo);
				$persona->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}