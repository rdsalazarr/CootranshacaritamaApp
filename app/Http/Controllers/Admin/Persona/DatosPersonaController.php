<?php

namespace App\Http\Controllers\Admin\Persona;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\generales;
use Exception, DB, URL;
use Carbon\Carbon;

class DatosPersonaController extends Controller
{
    public function index(Request $request)
	{ 
        $this->validate(request(),['tipo' => 'required', 'codigo' => 'required', 'frm' => 'required']);

        $generales             = new generales();
        $fechaHoraActual       = Carbon::now();
        $fechaActual           = $fechaHoraActual->format('Y-m-d');   
        $debeCrearRegistro     = false;
        $tipoConductores       = [];
        $agencias              = [];
        $persona               = [];
        $tpCateLicencias       = [];
        $conductorLicencia     = [];
        $historialLicencias    = [];
        $maxFechaVencimiento   = '';
        $cargoLaborales        = DB::table('cargolaboral')->select('carlabid','carlabnombre')->where('carlabid', '>', 3)->where('carlabactivo', true)->orderBy('carlabnombre')->get();
		$tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();
        $departamentos         = DB::table('departamento')->select('depaid','depanombre')->orderBy('depanombre')->get();
        $municipios            = DB::table('municipio')->select('muniid','munidepaid','muninombre')->orderBy('muninombre')->get();

        if($request->frm === 'CONDUCTOR'){
            $tipoConductores   = DB::table('tipoconductor')->select('tipconid','tipconnombre')->orderBy('tipconnombre')->get();
            $agencias          = DB::table('agencia')->select('agenid','agennombre')->where('agenactiva', true)->orderBy('agennombre')->get();
            $tpCateLicencias   = DB::table('tipocategorialicencia')->select('ticaliid','ticalinombre')->orderBy('ticalinombre')->get();
        }

        if($request->tipo !== 'I'){
            $url        = URL::to('/');
            $persona    = DB::table('persona as p')->select('p.persid','p.carlabid','p.tipideid','p.tipperid','p.persdepaidnacimiento','p.persmuniidnacimiento',
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
                                'a.asocfechaingreso', 'c.condid','c.tiescoid','c.tipconid','c.agenid','c.condfechaingreso',
                                DB::raw("(SELECT MAX(cl.conlicfechavencimiento) FROM conductorlicencia as cl
                                                        INNER JOIN conductor as c on c.condid = cl.condid
                                                        WHERE c.persid = p.persid) AS maxFechaVencimiento") )
                                ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                ->leftJoin('asociado as a', 'a.persid', '=', 'p.persid')
                                ->leftJoin('conductor as c', 'c.persid', '=', 'p.persid')
                                ->where('p.persid', $request->codigo)
                                ->first(); 

            if($request->frm === 'CONDUCTOR'){
                $debeCrearRegistro   = $generales->validarFechaVencimiento($fechaActual, $persona->maxFechaVencimiento);
                $comparadorConsulta  = ($debeCrearRegistro) ? '=' : '<';
                $documento           = $persona->persdocumento;
                $maxFechaVencimiento = $persona->maxFechaVencimiento;
                $conductorLicencia   = DB::table('conductorlicencia')
                                        ->select('conlicid','condid','ticaliid','conlicnumero','conlicfechaexpedicion','conlicfechavencimiento',
                                                'conlicextension', 'conlicnombrearchivooriginal', 'conlicnombrearchivoeditado', 'conlicrutaarchivo',
                                                DB::raw("CONCAT('archivos/persona/',$documento) as rutaAdjuntoLicencia"))
                                        ->where('condid', $persona->condid)
                                        ->where('conlicfechavencimiento', '>=', $fechaActual)
                                        ->where('conlicfechavencimiento', '<=', $maxFechaVencimiento)
                                        ->first();
                $conductorLicencia   = ($conductorLicencia) ? $conductorLicencia : [];
              
                $historialLicencias  = DB::table('conductorlicencia as cl')
                                                ->select('tcl.ticalinombre','cl.conlicnumero','cl.conlicfechaexpedicion','cl.conlicfechavencimiento',
                                                        'cl.conlicextension', 'cl.conlicnombrearchivooriginal', 'cl.conlicnombrearchivoeditado', 'cl.conlicrutaarchivo',
                                                        DB::raw("CONCAT('archivos/persona/',$documento) as rutaAdjuntoLicencia"))
                                                ->join('tipocategorialicencia as tcl', 'tcl.ticaliid', '=', 'cl.ticaliid')
                                                ->where('cl.condid', $persona->condid)
                                                ->whereRaw('cl.conlicfechavencimiento '.$comparadorConsulta.' (SELECT MAX(conlicfechavencimiento) FROM conductorlicencia WHERE condid = cl.condid )') ->get();
            }
        }

        return response()->json(["tipoCargoLaborales"  => $cargoLaborales,     "tipoIdentificaciones" => $tipoIdentificaciones,  "agencias"          => $agencias,
                                  "departamentos"      => $departamentos,      "municipios"           => $municipios,            "persona"           => $persona,
                                  "tipoConductores"    => $tipoConductores,    "tpCateLicencias"      => $tpCateLicencias,       "conductorLicencia" => $conductorLicencia,
                                  "historialLicencias" => $historialLicencias , "debeCrearRegistro"      => $debeCrearRegistro]);
	}

    public function show(Request $request)
    {
        $this->validate(request(),['codigo' => 'required', 'frm' => 'required']);

        $id   = $request->codigo;
        $frm  = $request->frm;
        $url  = URL::to('/');
        $persona = DB::table('persona as p')->select('cl.carlabnombre as nombreCargo', 'tp.tippernombre as nombreTipoPersona', 'p.persdocumento',
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
                                    DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutapem ) as rutaPem"),
                                    'a.asocfechaingreso', 'tc.tipconnombre','ag.agennombre','c.condfechaingreso',
                                    DB::raw('(SELECT COUNT(ascaesid) AS ascaesid FROM asociadocambioestado WHERE asocid = a.asocid ) AS totalCambioEstadoAsociado'),
                                    DB::raw('(SELECT COUNT(cocaesid) AS cocaesid FROM conductorcambioestado WHERE condid = c.condid ) AS totalCambioEstadoConductor'))
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->join('cargolaboral as cl', 'cl.carlabid', '=', 'p.carlabid')
                                    ->join('tipopersona as tp', 'tp.tipperid', '=', 'p.tipperid')
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
                                    ->leftJoin('asociado as a', 'a.persid', '=', 'p.persid')
                                    ->leftJoin('conductor as c', 'c.persid', '=', 'p.persid')
                                    ->leftJoin('tipoconductor as tc', 'tc.tipconid', '=', 'c.tipconid')
                                    ->leftJoin('agencia as ag', 'ag.agenid', '=', 'c.agenid')                                 
                                    ->orderBy('persprimernombre')->orderBy('perssegundonombre')
                                    ->orderBy('persprimerapellido')->orderBy('perssegundoapellido')
                                    ->where('p.persid', $id)->first();

        $licenciasConducion  = [];
        if($frm === 'CONDUCTOR'){
            $documento           = $persona->persdocumento;
            $licenciasConducion  = DB::table('conductorlicencia as cl')
                        ->select('tcl.ticalinombre','cl.conlicnumero','cl.conlicfechaexpedicion','cl.conlicfechavencimiento',
                                'cl.conlicextension', 'cl.conlicnombrearchivooriginal', 'cl.conlicnombrearchivoeditado', 'cl.conlicrutaarchivo',
                                DB::raw("CONCAT('archivos/persona/',$documento) as rutaAdjuntoLicencia"))
                        ->join('tipocategorialicencia as tcl', 'tcl.ticaliid', '=', 'cl.ticaliid')
                        ->join('conductor as c', 'c.condid', '=', 'cl.condid')
                        ->where('c.persid', $id)->get();
        }

        $cambiosEstadoAsociado  = [];
        if($persona->totalCambioEstadoAsociado > 0 ){
            $cambiosEstadoAsociado =  DB::table('asociadocambioestado as ace')
                                    ->select('ace.ascaesfechahora as fecha','ace.ascaesobservacion as observacion','tea.tiesasnombre as estado',
                                        DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                    ->join('asociado as a', 'a.asocid', '=', 'ace.asocid')
                                    ->join('tipoestadoasociado as tea', 'tea.tiesasid', '=', 'ace.tiesasid')
                                    ->join('usuario as u', 'u.usuaid', '=', 'ace.ascaesusuaid')
                                    ->where('a.persid', $id)->get();
        }

        $cambiosEstadoConductor = [];
        if($persona->totalCambioEstadoConductor > 0 ){
            $cambiosEstadoConductor =  DB::table('conductorcambioestado as cce')
                                    ->select('cce.cocaesfechahora as fecha','cce.cocaesobservacion as observacion','tec.tiesconombre as estado',
                                        DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                    ->join('conductor as c', 'c.condid', '=', 'cce.condid')
                                    ->join('tipoestadoconductor as tec', 'tec.tiescoid', '=', 'cce.tiescoid')
                                    ->join('usuario as u', 'u.usuaid', '=', 'cce.cocaesusuaid')
                                    ->where('c.persid', $id)->get();
        }

        return response()->json(["persona" => $persona,                              "cambiosEstadoAsociado" => $cambiosEstadoAsociado, 
                                "cambiosEstadoConductor" => $cambiosEstadoConductor, "licenciasConducion" => $licenciasConducion]);
    } 
}