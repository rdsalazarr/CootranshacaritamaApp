<?php

namespace App\Http\Controllers\Admin\Radicacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class ShowDocumentoEntranteController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['codigo' => 'required']);

        $codigo = $request->codigo;
        $copias = [];

        $radicado   = DB::table('radicaciondocumentoentrante as rde')
                        ->select('tm.tipmednombre as nombreTipoMedio','terde.tierdenombre as estadoActual','d.depenombre as dependencia',
                                'm.muninombre as municipio','dp.depanombre as depertamento','rde.radoenfechadocumento','rde.radoenfechallegada',
                                'rde.radoenpersonaentregadocumento','rde.radoenasunto', 'rde.radoendescripcionanexo','rde.radoenobservacion',
                                DB::raw("CONCAT(rde.radoenanio,' - ', rde.radoenconsecutivo) as consecutivo"),'radoenfechahoraradicado','radoenfechamaximarespuesta',
                                DB::raw("if(rde.radoentieneanexo = 1 ,'Sí', 'No') as tieneAnexos"),
						        DB::raw("if(rde.radoentienecopia = 1 ,'Sí', 'No') as tieneCopias"),
                                DB::raw("if(rde.radoenrequiererespuesta = 1 ,'Sí', 'No') as requiereRespuesta"),
                                'ti.tipidenombre as tipoIdentificacion','prd.peradodocumento','prd.peradoprimernombre','prd.peradosegundonombre','prd.peradoprimerapellido',
                                'prd.peradosegundoapellido', 'prd.peradodireccion','prd.peradotelefono','prd.peradocorreo','prd.peradocodigodocumental',
                                DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"),
                                DB::raw('(SELECT COUNT(radoedid) AS radoedid FROM radicaciondocentdependencia WHERE radoenid = rde.radoenid) AS totalCopias'))
                        ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                        ->join('tipomedio as tm', 'tm.tipmedid', '=', 'rde.tipmedid')
                        ->join('tipoestadoraddocentrante as terde', 'terde.tierdeid', '=', 'rde.tierdeid')
                        ->join('dependencia as d', 'd.depeid', '=', 'rde.depeid')
                        ->join('departamento as dp', 'dp.depaid', '=', 'rde.depaid')
                        ->join('municipio as m', function($join)
							{
								$join->on('m.muniid', '=', 'rde.muniid');
								$join->on('m.munidepaid', '=', 'rde.depaid'); 
							})
                        ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'prd.tipideid')
                        ->leftJoin('usuario as u', 'u.usuaid', '=', 'rde.usuaid')
                        ->where('rde.radoenid', $codigo)->first();

        if($radicado->totalCopias > 0){
            $copias  =  DB::table('radicaciondocentdependencia as rded')
                                    ->select('rded.depeid','d.depenombre as dependencia', 'rded.radoedfechahorarecibido as fecha',
                                    DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                                    ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                                    ->leftJoin('usuario as u', 'u.usuaid', '=', 'rded.radoedsuaid')
                                    ->where('rded.radoenid', $codigo)->get();
        }

        $anexos  =  DB::table('radicaciondocentanexo as rdea')
                            ->select('rdea.radoeaid as id','rdea.radoeanombreanexooriginal as nombreOriginal','rdea.radoeanombreanexoeditado as nombreEditado',
                            'rdea.radoearutaanexo as rutaAnexo',DB::raw("if(rdea.radoearequiereradicado = 1 ,'Sí', 'No') as radicarDocumento"), 'rde.radoenanio as anio',
                            DB::raw("CONCAT('archivos/radicacion/documentoEntrante/',rde.radoenanio,'/', rdea.radoearutaanexo) as rutaDescargar"))
                            ->join('radicaciondocumentoentrante as rde', 'rde.radoenid', '=', 'rdea.radoenid')
                            ->where('rdea.radoenid', $codigo)->get();

        $estados =  DB::table('radicaciondocentcambioestado as rdece')
						->select('rdece.radecefechahora as fecha','rdece.radeceobservacion as observacion','terde.tierdenombre as estado',
						    DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                        ->join('tipoestadoraddocentrante as terde', 'terde.tierdeid', '=', 'rdece.tierdeid')
						->join('usuario as u', 'u.usuaid', '=', 'rdece.radeceusuaid')
						->where('rdece.radoenid', $codigo)->get();

        return response()->json(["radicado" => $radicado, "copias" => $copias, "anexos" => $anexos, "estados" => $estados]);
    }
}