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

        try{
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
            $conductorCertificados = [];
            $maxFechaVencimiento   = '';
            $cargoLaborales        = DB::table('cargolaboral')->select('carlabid','carlabnombre')->where('carlabid', '>', 3)->where('carlabactivo', true)->orderBy('carlabnombre')->get();
            $tipoIdentificaciones  = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4'])->orderBy('tipidenombre')->get();
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
                                    'p.persdepaidexpedicion','p.persmuniidexpedicion','p.persdocumento','p.perstienefirmadigital','p.perstienefirmaelectronica',
                                    'p.persprimernombre','p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                    'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                    'p.persgenero','p.persrutafoto','p.persrutafirma','p.persactiva','p.persrutapem','p.persrutacrt','p.persclavecertificado',
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaPersona"),
                                    DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutacrt ) as rutaCrt"),
                                    DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutapem ) as rutaPem"),
                                    DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',a.asocrutacertificado ) as rutaCertificado"),
                                    'a.asocrutacertificado','a.asocfechaingreso', 'c.condid','c.tiescoid','c.tipconid','c.agenid','c.condfechaingreso',
                                    DB::raw('(SELECT COUNT(concerid) AS concerid FROM conductorcertificado WHERE condid = c.condid ) AS totalCertificadoConductor'),
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


                    if($persona->totalCertificadoConductor > 0 ){               
                        $conductorCertificados  = DB::table('conductorcertificado as cc')
                                                    ->select('cc.concerid as id', 'cc.concernombrearchivooriginal as nombreOriginal', 
                                                    'cc.concernombrearchivoeditado as nombreEditado', 'cc.concerrutaarchivo as rutaCertificado',
                                                     DB::raw("CONCAT($documento) as documento"),
                                                     DB::raw("CONCAT('archivos/persona/',$documento,'/', cc.concerrutaarchivo) as rutaDescargar"))
                                                    ->join('conductor as c', 'c.condid', '=', 'cc.condid')
                                                    ->where('c.persid', $request->codigo)->get();
                    }
                }
            }

            return response()->json(['success'              => true,                  "tipoCargoLaborales" => $cargoLaborales,     "tipoIdentificaciones" => $tipoIdentificaciones,  
                                    "agencias"              => $agencias,             "departamentos"      => $departamentos,      "municipios"           => $municipios,           
                                    "persona"               => $persona,              "tipoConductores"    => $tipoConductores,    "tpCateLicencias"      => $tpCateLicencias,      
                                    "conductorLicencia"     => $conductorLicencia,    "historialLicencias" => $historialLicencias, "debeCrearRegistro"    => $debeCrearRegistro,
                                    "conductorCertificados" => $conductorCertificados]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function show(Request $request)
    {
        $this->validate(request(),['codigo' => 'required', 'frm' => 'required']);

        try{
            $id   = $request->codigo;
            $frm  = $request->frm;
            $url  = URL::to('/');
            $persona = DB::table('persona as p')->select('cl.carlabnombre as nombreCargo', 'tp.tippernombre as nombreTipoPersona', 'p.persdocumento',
                                        'p.persprimernombre','p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                        'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular','p.persgenero',
                                        'p.persrutafoto','p.persrutafirma','p.perstienefirmaelectronica as firmaElectronica', 'p.perstienefirmadigital as firmaDigital',
                                        'dn.depanombre as nombreDeptoNacimiento', 'mn.muninombre as nombreMunicipioNacimiento',   
                                        'de.depanombre as nombreDeptoExpedicion', 'me.muninombre as nombreMunicipioExpedicion',
                                        DB::raw("if(p.persgenero = 'M' ,'Masculino', 'Femenino') as genero"),
                                        DB::raw("if(p.perstienefirmaelectronica = 1 ,'Sí', 'No') as tieneFirmaElectronica"),
                                        DB::raw("if(p.perstienefirmadigital = 1 ,'Sí', 'No') as tieneFirmaDigital"),
                                        DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as nombreTipoIdentificacion"),
                                        DB::raw("if(p.persactiva = 1 ,'Sí', 'No') as estado"),
                                        DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"),
                                        DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaPersona"),
                                        DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutacrt ) as rutaCrt"),
                                        DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutapem ) as rutaPem"),
                                        DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',a.asocrutacertificado ) as rutaCertificado"),
                                        'a.asocrutacertificado','a.asocfechaingreso', 'tc.tipconnombre','ag.agennombre','c.condfechaingreso',
                                        DB::raw('(SELECT COUNT(ascaesid) AS ascaesid FROM asociadocambioestado WHERE asocid = a.asocid ) AS totalCambioEstadoAsociado'),
                                        DB::raw('(SELECT COUNT(cocaesid) AS cocaesid FROM conductorcambioestado WHERE condid = c.condid ) AS totalCambioEstadoConductor'),
                                        DB::raw('(SELECT COUNT(concerid) AS concerid FROM conductorcertificado WHERE condid = c.condid ) AS totalCertificadoConductor'),
                                        DB::raw('(SELECT COUNT(soliid) AS soliid FROM solicitud WHERE condid = c.condid ) AS totalSolicitudConductor'))
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
                                        ->where('p.persid', $id)->first();

            $licenciasConducion     = [];
            $conductorCertificados  = [];
            $cambiosEstadoConductor = [];
            $solicitudConductores   = [];
            if($frm === 'CONDUCTOR'){
                $documento           = $persona->persdocumento;
                $licenciasConducion  = DB::table('conductorlicencia as cl')
                                        ->select('tcl.ticalinombre','cl.conlicnumero','cl.conlicfechaexpedicion','cl.conlicfechavencimiento',
                                                'cl.conlicextension', 'cl.conlicnombrearchivooriginal', 'cl.conlicnombrearchivoeditado', 'cl.conlicrutaarchivo',
                                                DB::raw("CONCAT('archivos/persona/',$documento) as rutaAdjuntoLicencia"))
                                        ->join('tipocategorialicencia as tcl', 'tcl.ticaliid', '=', 'cl.ticaliid')
                                        ->join('conductor as c', 'c.condid', '=', 'cl.condid')
                                        ->where('c.persid', $id)->get();

                if($persona->totalCertificadoConductor > 0 ){
                    $conductorCertificados  = DB::table('conductorcertificado as cc')
                                                    ->select('cc.concerid as id', 'cc.concernombrearchivooriginal as nombreOriginal', 
                                                    'cc.concernombrearchivoeditado as nombreEditado', 'cc.concerrutaarchivo as rutaCertificado',
                                                     DB::raw("CONCAT($documento) as documento"),
                                                     DB::raw("CONCAT('archivos/persona/',$documento,'/', cc.concerrutaarchivo) as rutaDescargar"))
                                                    ->join('conductor as c', 'c.condid', '=', 'cc.condid')
                                                    ->where('c.persid', $id)->get();
                }

                if($persona->totalSolicitudConductor > 0 ){
                    $solicitudConductores   = DB::table('solicitud as s')
                                                    ->select('s.soliid', 's.solifechahoraregistro',
                                                        DB::raw('SUBSTRING(s.solimotivo, 1, 200) AS asunto'),'ts.tipsolnombre as tipoSolicitud',
                                                        DB::raw("CONCAT(rde.radoenanio,'-', rde.radoenconsecutivo) as consecutivo"),
                                                        DB::raw("CONCAT(prd.peradoprimernombre,' ',IFNULL(prd.peradosegundonombre,''),' ',prd.peradoprimerapellido,' ',IFNULL(prd.peradosegundoapellido,'')) as nombrePersonaRadica"))
                                                    ->join('radicaciondocumentoentrante as rde', 'rde.radoenid', '=', 's.radoenid')
                                                    ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                                                    ->join('tiposolicitud as ts', 'ts.tipsolid', '=', 's.tipsolid')
                                                    ->join('conductor as c', 'c.condid', '=', 's.condid')
                                                    ->where('c.persid', $id)
                                                    ->orderBy('rde.radoenid', 'Desc')->get();
                }

                if($persona->totalCambioEstadoConductor > 0 ){
                    $cambiosEstadoConductor =  DB::table('conductorcambioestado as cce')
                                            ->select('cce.cocaesfechahora as fecha','cce.cocaesobservacion as observacion','tec.tiesconombre as estado',
                                                DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                            ->join('conductor as c', 'c.condid', '=', 'cce.condid')
                                            ->join('tipoestadoconductor as tec', 'tec.tiescoid', '=', 'cce.tiescoid')
                                            ->join('usuario as u', 'u.usuaid', '=', 'cce.cocaesusuaid')
                                            ->where('c.persid', $id)->get();
                }
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

            return response()->json(['success' => true,   "persona" => $persona,         "cambiosEstadoAsociado" => $cambiosEstadoAsociado, 
                                    "cambiosEstadoConductor" => $cambiosEstadoConductor, "licenciasConducion"    => $licenciasConducion,
                                    "conductorCertificados"   => $conductorCertificados, "solicitudConductores"  => $solicitudConductores]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }
}