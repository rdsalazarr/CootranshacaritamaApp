<?php

namespace App\Http\Controllers\Admin\Persona;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB, URL;

class DatosPersonaController extends Controller
{
    public function index(Request $request)
	{ 
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required']);

        $cargoLaborales        = DB::table('cargolaboral')->select('carlabid','carlabnombre')->where('carlabactivo',1)->orderBy('carlabnombre')->get();
		$tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();		
        $departamentos         = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get();
        $municipios            = DB::table('municipio')->select('muniid','munidepaid','muninombre')->orderBy('muninombre')->get();
        $persona               = [];
        if($request->tipo !== 'I'){
            $url  = URL::to('/');
            $persona = DB::table('persona as p')->select('p.persid','p.carlabid','p.tipideid','p.tirelaid','p.persdepaidnacimiento','p.persmuniidnacimiento',
                                'p.persdepaidexpedicion','p.persmuniidexpedicion','p.persdocumento','p.perstienefirmadigital',
                                'p.persprimernombre','p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                'p.persgenero','p.persrutafoto','p.persrutafirma','p.persactiva','p.persrutapem','p.persrutacrt','p.persclavecertificado',
                                DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"),
                                DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaPersona"),
                                DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutacrt ) as rutaCrt"),
                                DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutapem ) as rutaPem"),
                                'a.asocfechaingreso as fechaIngresoAsocido', 'a.asocfechaingreso as fechaIngresoConductor')
                                ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                ->leftJoin('asociado as a', 'a.persid', '=', 'p.persid')
                                ->where('p.persid', $request->codigo)
                                ->first();
        }
 
        return response()->json(["tipoCargoLaborales" => $cargoLaborales, "tipoIdentificaciones" => $tipoIdentificaciones,
                                  "departamentos"     => $departamentos,  "municipios"           => $municipios,            "persona" => $persona ]);
	}

    public function show(Request $request)
    {  
        $id   = $request->codigo;
        $url  = URL::to('/');    
        $data = DB::table('persona as p')->select('cl.carlabnombre as nombreCargo', 'trl.tirelanombre as nombreTipoRelacionLaboral', 'p.persdocumento',
                                    'p.persprimernombre','p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                    'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                    'p.persgenero','p.persrutafoto','p.persrutafirma','p.perstienefirmadigital as firmaDigital',
                                    'dn.depanombre as nombreDeptoNacimiento', 'mn.muninombre as nombreMunicipioNacimiento',   
                                    'de.depanombre as nombreDeptoExpedicion', 'me.muninombre as nombreMunicipioExpedicion',  
                                    DB::raw("if(p.persgenero = 'M' ,'Masculino', 'Femenino') as genero"),
                                    DB::raw("if(p.perstienefirmadigital = 1 ,'Sí', 'No') as tieneFirmaDigital"),
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as nombreTipoIdentificacion"),
                                    DB::raw("if(p.persactiva = 1 ,'Sí', 'No') as estado"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaPersona"),
                                    DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutacrt ) as rutaCrt"),
                                    DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutapem ) as rutaPem"))
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->join('cargolaboral as cl', 'cl.carlabid', '=', 'p.carlabid')
                                    ->join('tiporelacionlaboral as trl', 'trl.tirelaid', '=', 'p.tirelaid')
                                    ->join('departamento as dn', 'dn.depaid', '=', 'p.persdepaidnacimiento') 
                                    ->join('municipio as mn', function($join)
                                    {
                                        $join->on('mn.munidepaid', '=', 'p.persdepaidnacimiento');
                                        $join->on('mn.muniid', '=', 'p.persmuniidnacimiento'); 
                                    })

                                    ->join('departamento as de', 'de.depaid', '=', 'p.persdepaidexpedicion') 
                                    ->join('municipio as me', function($join)
                                    {
                                        $join->on('me.munidepaid', '=', 'p.persdepaidexpedicion');
                                        $join->on('me.muniid', '=', 'p.persmuniidexpedicion'); 
                                    })

                                    ->orderBy('persprimernombre')->orderBy('perssegundonombre')
                                    ->orderBy('persprimerapellido')->orderBy('perssegundoapellido')
                                    ->where('p.persid', $id)->first();
//nombreTipoRelacionLaboral
        return response()->json(["data" => $data]);
    }
}
