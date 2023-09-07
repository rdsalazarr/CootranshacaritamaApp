<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use DB;

class PersonaController extends Controller
{
    public function index()
    {  
        $data = DB::table('persona as p')->select('p.persid','p.carlabid','p.tipideid','p.tirelaid','p.persdepaidexpedicion','p.persmuniidexpedicion','p.persdocumento',
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
		$tipoidentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();
		$tiporelacionlaborales = DB::table('tiporelacionlaboral')->select('tirelaid','tirelanombre')->orderBy('tirelanombre')->get();
        $cargolaborales        = DB::table('cargolaboral')->select('carlabid','carlabnombre')->where('carlabactivo',1)->orderBy('carlabnombre')->get();
        return response()->json(["tipoidentificaciones" => $tipoidentificaciones, "tiporelacionlaborales" => $tiporelacionlaborales, "cargolaborales" => $cargolaborales ]);
	}

    public function salve(Request $request)
	{
        $id      = $request->id;
        $persona = ($id != 000) ? Persona::findOrFail($id) : new Persona();

	    $this->validate(request(),[
                'documento'             => 'required|string|max:15|unique:persona,documento,'.$persona->persid.',persid,tipideid,'.$request->tipoIdentificacion, 
                'cargo'                 => 'required|numeric',
                'tipoIdentificacion'    => 'required|numeric',
                'tipoRelacionLaboral'   => 'required|numeric',
                'depatamentoExpedicion' => 'required|numeric',
                'municipioExpedicion'   => 'required|numeric',
	   	        'documento'             => 'required|string|min:6|max:15',
                'primerNombre'          => 'required|string|min:4|max:40',
                'segundoNombre'         => 'nullable|string|min:4|max:40',
                'primerApellido'        => 'required|string|min:4|max:40',
                'segundoApellido'       => 'nullable|string|min:4|max:40',
                'fechaNacimiento' 	    => 'nullable|date|date_format:Y-m-d',
                'direccion'             => 'required|string|min:4|max:100',
                'correo'                => 'nullable|email|string|max:80',
                'fechaExpedicion' 	    => 'nullable|date|date_format:Y-m-d',
                'telefonoFijo'          => 'nullable|string|max:20',
                'numeroCelular'         => 'nullable|string|max:20',
                'genero'                => 'required',
	            'estado'                => 'required',
                'firma' 	            => 'nullable|mimes:png,PNG|max:1000',
                'foto'     	            => 'nullable|mimes:png,jpg,PNG,JPG|max:1000'
	        ]);

        try {

            $rutaFoto = '';
            $rutaFirma = '';

            $persona->carlabid               = $request->cargo;
            $persona->tipideid               = $request->tipoIdentificacion;
            $persona->tirelaid               = $request->tipoRelacionLaboral;
            $persona->persdepaidexpedicion   = $request->depatamentoExpedicion;
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
