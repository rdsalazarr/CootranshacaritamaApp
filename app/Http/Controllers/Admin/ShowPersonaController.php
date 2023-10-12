<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB, URL;

class ShowPersonaController extends Controller
{
    public function index(Request $request)
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