<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Util\generales;
use File, DB;

class PersonaController extends Controller
{
    public function index()
    {  
        $data = DB::table('persona as p')->select('p.persid','p.carlabid','p.tipideid','p.tirelaid','p.persdepaidnacimiento','p.persmuniidnacimiento',
                                    'p.persdepaidexpedicion','p.persmuniidexpedicion','p.persdocumento',
                                    'p.persprimernombre','p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                    'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                    'p.persgenero','p.persrutafoto','p.persrutafirma','p.persactiva',
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                    DB::raw("CONCAT(ti.tipidesigla,'_', ti.tipidenombre) as tipoIdentificacion"),
                                    DB::raw("if(p.persactiva = 1 ,'Sí', 'No') as estado"))
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->orderBy('persprimernombre')->orderBy('perssegundonombre')
                                    ->orderBy('persprimerapellido')->orderBy('perssegundoapellido')->get();

        return response()->json(["data" => $data]);
    }

    public function datos()
	{ 
        $cargoLaborales        = DB::table('cargolaboral')->select('carlabid','carlabnombre')->where('carlabactivo',1)->orderBy('carlabnombre')->get();
		$tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();
		$tipoRelacionLaborales = DB::table('tiporelacionlaboral')->select('tirelaid','tirelanombre')->orderBy('tirelanombre')->get();
        $departamentos         = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get();
        $municipios            = DB::table('municipio')->select('muniid','munidepaid','muninombre')->orderBy('muninombre')->get();
       
        return response()->json(["tipoCargoLaborales" => $cargoLaborales, "tipoIdentificaciones" => $tipoIdentificaciones,
                                 "tipoRelacionLaborales" => $tipoRelacionLaborales, "departamentos" => $departamentos, "municipios" => $municipios ]);
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
                'firma' 	             => 'nullable|mimes:png,PNG|max:1000',
                'fotografia'             => 'nullable|mimes:png,jpg,PNG,JPG|max:1000'
	        ]);

        try {

			$funcion 		 = new generales();          
            $rutaCarpeta     = public_path().'/archivos/persona/'.$request->documento;
            $carpetaServe    = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            if($request->hasFile('firma')){
				$file = $request->file('firma');
				$nombreOriginal = $file->getclientOriginalName();
				$filename   = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension  = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$nombre_escritura = 'Firma_'.$request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $nombre_escritura);
                $rutaFirma = $nombre_escritura;
			}else{
				$rutaFirma = $request->rutaFirma_old;
			}

            if($request->hasFile('fotografia')){
				$file = $request->file('fotografia');
				$nombreOriginal = $file->getclientOriginalName();
				$filename   = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension  = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$nombre_escritura = $request->documento."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $nombre_escritura);
                $rutaFoto = $nombre_escritura;
			}else{
				$rutaFoto = $request->rutaFoto_old;
			}

            //rutaFoto_old

            $rutaFoto = '';
           

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
            $persona->persrutafoto           = $rutaFoto;
            $persona->persrutafirma          = $rutaFirma;
            $persona->persactiva             = $request->estado;
            $persona->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$subPersona = DB::table('dependencia')
					->select('depeid')
					->where('depeid', $request->codigo)->first();

		if($subPersona){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a una serie documental del sistema']);
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
