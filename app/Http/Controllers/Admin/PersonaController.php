<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Util\redimencionarImagen;
use Exception, File, DB, URL;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Util\generales;

class PersonaController extends Controller
{
    public function index()
    { 
        $data = DB::table('persona as p')->select('p.persid','p.persdocumento', 'p.persdireccion','p.perscorreoelectronico',
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as tipoIdentificacion"),
                                    DB::raw("if(p.persactiva = 1 ,'Sí', 'No') as estado"))
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                    ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{ 
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required']);

        $cargoLaborales        = DB::table('cargolaboral')->select('carlabid','carlabnombre')->where('carlabactivo',1)->orderBy('carlabnombre')->get();
		$tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();
		$tipoRelacionLaborales = DB::table('tiporelacionlaboral')->select('tirelaid','tirelanombre')->orderBy('tirelanombre')->get();
        $departamentos         = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get();
        $municipios            = DB::table('municipio')->select('muniid','munidepaid','muninombre')->orderBy('muninombre')->get();
        $data                  = [];
        if($request->tipo !== 'I'){
            $url  = URL::to('/');
            $data = DB::table('persona as p')->select('p.persid','p.carlabid','p.tipideid','p.tirelaid','p.persdepaidnacimiento','p.persmuniidnacimiento',
                                'p.persdepaidexpedicion','p.persmuniidexpedicion','p.persdocumento','p.perstienefirmadigital',
                                'p.persprimernombre','p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                'p.persgenero','p.persrutafoto','p.persrutafirma','p.persactiva','p.persrutapem','p.persrutacrt','p.persclavecertificado',
                                DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"),
                                DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaPersona"),
                                DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutacrt ) as rutaCrt"),
                                DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutapem ) as rutaPem"))
                                ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                ->where('p.persid', $request->codigo)
                                ->first();
        }
 
        return response()->json(["tipoCargoLaborales" => $cargoLaborales, "tipoIdentificaciones" => $tipoIdentificaciones,
                                 "tipoRelacionLaborales" => $tipoRelacionLaborales, "departamentos" => $departamentos, "municipios" => $municipios, "persona" => $data ]);
	}

    public function salve(Request $request)
	{
        $id      = $request->codigo;
        $persona = ($id != 000) ? Persona::findOrFail($id) : new Persona();
 
	    $this->validate(request(),[
                'documento'              => 'required|string|max:15|unique:persona,persdocumento,'.$id.',persid,tipideid,'.$request->tipoIdentificacion, 
                'cargo'                  => 'required|numeric',
                'tipoIdentificacion'     => 'required|numeric',
                'tipoRelacionLaboral'    => 'required|numeric',
                'departamentoNacimiento' => 'required|numeric',
                'municipioNacimiento'    => 'required|numeric',
                'departamentoExpedicion' => 'required|numeric',
                'municipioExpedicion'    => 'required|numeric',
                'primerNombre'           => 'required|string|min:4|max:40',
                'segundoNombre'          => 'nullable|string|min:4|max:40',
                'primerApellido'         => 'required|string|min:4|max:40',
                'segundoApellido'        => 'nullable|string|min:4|max:40',
                'fechaNacimiento' 	     => 'nullable|date|date_format:Y-m-d',
                'direccion'              => 'required|string|min:4|max:100',
                'correo'                 => 'nullable|email|string|max:80',
                'fechaExpedicion' 	     => 'nullable|date|date_format:Y-m-d',
                'telefonoFijo'           => 'nullable|string|max:20',
                'numeroCelular'          => 'nullable|string|max:20',
                'genero'                 => 'required',
	            'estado'                 => 'required',
                'firmaDigital'           => 'required',
                'firma' 	             => 'nullable|mimes:png,PNG|max:1000',
                'fotografia'             => 'nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG|max:1000',
                'claveCertificado'       => 'nullable|string|max:20'
	        ]);  

        DB::beginTransaction();
        try {

			$redimencionarImagen = new redimencionarImagen();
            $funcion 		     = new generales();
            $rutaCarpeta         = public_path().'/archivos/persona/'.$request->documento;
            $carpetaServe        = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            if($request->hasFile('firma')){
				$file           = $request->file('firma');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaFirma      = 'Firma_'.$request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFirma);
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaFirma, 280, 140);//Se redimenciona a un solo tipo
			}else{
				$rutaFirma = $request->rutaFirmaOld;
			}

            if($request->hasFile('fotografia')){
				$file           = $request->file('fotografia');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaFotografia = $request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFotografia);
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaFotografia, 210, 270);//Se redimenciona a un solo tipo
			}else{
				$rutaFotografia = $request->rutaFotoOld;
			}

            if($request->hasFile('rutaCrt')){
				$file           = $request->file('rutaCrt');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaCrt        = $request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaCrt);
                $rutaCrt        = Crypt::encrypt($rutaCrt);
			}else{
				$rutaCrt = $request->rutaCrtOld;
			}

            if($request->hasFile('rutaPem')){
				$file           = $request->file('rutaPem');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaPem        = $request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaPem);
                $rutaPem        = Crypt::encrypt($rutaPem);
			}else{
				$rutaPem = $request->rutaPemOld;
			}

            $persona->carlabid               = $request->cargo;
            $persona->tipideid               = $request->tipoIdentificacion;
            $persona->tirelaid               = $request->tipoRelacionLaboral;
            $persona->persdepaidnacimiento   = $request->departamentoNacimiento;
            $persona->persmuniidnacimiento   = $request->municipioNacimiento;
            $persona->persdepaidexpedicion   = $request->departamentoExpedicion;
            $persona->persmuniidexpedicion   = $request->municipioExpedicion;
            $persona->persdocumento          = $request->documento;
            $persona->persprimernombre       = $request->primerNombre;
            $persona->perssegundonombre      = $request->segundoNombre;
            $persona->persprimerapellido     = $request->primerApellido;
            $persona->perssegundoapellido    = $request->segundoApellido;
            $persona->persfechanacimiento    = $request->fechaNacimiento;
            $persona->persdireccion          = $request->direccion;
            $persona->perscorreoelectronico  = $request->correo;
            $persona->persfechadexpedicion   = $request->fechaExpedicion;
            $persona->persnumerotelefonofijo = $request->telefonoFijo;
            $persona->persnumerocelular      = $request->numeroCelular;
            $persona->persgenero             = $request->genero;
            $persona->persrutafoto           = $rutaFotografia;
            $persona->persrutafirma          = $rutaFirma;
            $persona->perstienefirmadigital  = $request->firmaDigital;
            $persona->persclavecertificado   = $request->claveCertificado;
            $persona->persrutacrt            = $rutaCrt;
            $persona->persrutapem            = $rutaPem;
            $persona->persactiva             = $request->estado;
            $persona->save();

        	DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$dependencia = DB::table('dependencia')->select('depeid')->where('depejefeid', $request->codigo)->first();
        $dependenciapersona = DB::table('dependenciapersona')->select('depperid')->where('depperpersid', $request->codigo)->first();

		if($dependencia || $dependenciapersona){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una dependencia del sistema']);
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
