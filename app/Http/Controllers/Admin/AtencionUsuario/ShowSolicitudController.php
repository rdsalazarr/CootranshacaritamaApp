<?php

namespace App\Http\Controllers\Admin\AtencionUsuario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class ShowSolicitudController extends Controller
{
    public function index(Request $request)
	{
        $this->validate(request(),['codigo' => 'required']);
        try{

            $solicitud   = DB::table('solicitud as s')
                        ->select('s.radoenid','ti.tipidenombre as tipoIdentificacion','ts.tipsolnombre as tipoSolicitud','tms.timesonombre as tipoMedio',
                                 DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                                 DB::raw("CONCAT(p.persdocumento,' ', p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreConductor"),
                                's.solifechahoraincidente','s.solimotivo','s.solifechahoraregistro',
                                's.soliobservacion','s.soliradicado','prd.tipideid','prd.peradodocumento','prd.peradoprimernombre','prd.peradosegundonombre',
                                'prd.peradoprimerapellido','prd.peradosegundoapellido', 'prd.peradodireccion','prd.peradotelefono','prd.peradocorreo')
                        ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 's.peradoid')
                        ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'prd.tipideid')
                        ->join('tiposolicitud as ts', 'ts.tipsolid', '=', 's.tipsolid')
                        ->join('tipomediosolicitud as tms', 'tms.timesoid', '=', 's.timesoid')
                        ->leftJoin('vehiculo as v', 'v.vehiid', '=', 's.vehiid')
                        ->leftJoin('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                        ->leftJoin('conductor as c', 'c.condid', '=', 's.condid')
                        ->leftJoin('persona as p', 'p.persid', '=', 'c.persid')
                        ->where('s.soliid', $request->codigo)->first();

            $anexosRadicados  =  DB::table('radicaciondocentanexo as rdea')
                        ->select('rdea.radoeaid as id','rdea.radoeanombreanexooriginal as nombreOriginal','rdea.radoeanombreanexoeditado as nombreEditado',
                        'rdea.radoearutaanexo as rutaAnexo',DB::raw("if(rdea.radoearequiereradicado = 1 ,'Sí', 'No') as radicarDocumento"), 'rde.radoenanio as anio',
                        DB::raw("CONCAT('archivos/radicacion/documentoEntrante/',rde.radoenanio,'/', rdea.radoearutaanexo) as rutaDescargar"))
                        ->join('radicaciondocumentoentrante as rde', 'rde.radoenid', '=', 'rdea.radoenid')
                        ->where('rdea.radoenid', $solicitud->radoenid)->get();
        
            return response()->json(['success' => true, "solicitud" => $solicitud, "anexos" => $anexosRadicados]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }
}