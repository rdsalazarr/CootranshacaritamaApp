<?php

namespace App\Http\Controllers\Admin\Radicacion;
use App\Models\RadicacionDocumentoEntranteCambioEstado;
use App\Models\RadicacionDocumentoEntrante;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon;
use Auth, DB;

class AnularDocumentoEntranteController extends Controller
{
    public function index(Request $request)
	{
        $this->validate(request(),['consecutivo' => 'required|numeric', 'anyo' => 'required|numeric']);
        $consecutivo = str_pad( $request->consecutivo,  4, "0", STR_PAD_LEFT);
        $anyo        = $request->anyo;

        $radicado   = DB::table('radicaciondocumentoentrante as rde')
                        ->select('rde.radoenid','tm.tipmednombre as nombreTipoMedio','terde.tierdenombre as estadoActual','d.depenombre as dependencia',
                                'm.muninombre as municipio','dp.depanombre as depertamento','rde.radoenfechadocumento','rde.radoenfechallegada',
                                'rde.radoenpersonaentregadocumento','rde.radoenasunto', 'rde.radoendescripcionanexo','rde.radoenobservacion',
                                DB::raw("CONCAT(rde.radoenanio,' - ', rde.radoenconsecutivo) as consecutivo"),'radoenfechahoraradicado','radoenfechamaximarespuesta',
                                DB::raw("if(rde.radoentieneanexo = 1 ,'Sí', 'No') as tieneAnexos"),
                                DB::raw("if(rde.radoentienecopia = 1 ,'Sí', 'No') as tieneCopias"),
                                DB::raw("if(rde.radoenrequiererespuesta = 1 ,'Sí', 'No') as requiereRespuesta"),
                                'ti.tipidenombre as tipoIdentificacion','prd.peradodocumento','prd.peradoprimernombre','prd.peradosegundonombre','prd.peradoprimerapellido',
                                'prd.peradosegundoapellido', 'prd.peradodireccion','prd.peradotelefono','prd.peradocorreo','prd.peradocodigodocumental',
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
                        ->whereNotIn('terde.tierdeid', [4, 5])
                        ->where('rde.radoenanio', $anyo)
                        ->where('rde.radoenconsecutivo', $consecutivo)->first();

        $array = ($radicado !== null) ? ['success' => true, "data" => $radicado] : ['success' => false, "data" => "No se encontro información con los datos suministrados"];
        return response()->json($array);      
    }

    public function anular(Request $request)
	{
        $this->validate(request(),['codigo' => 'required|numeric', 'observacionCambio' => 'required|string|min:20|max:500']);

        $estado          = '5';
        $codigo          = $request->codigo;
        $fechaHoraActual = Carbon::now();
        $dataCopias      = [];
        DB::beginTransaction();
        try {

            $radicaciondocumentoentrante           = RadicacionDocumentoEntrante::findOrFail($codigo);
            $radicaciondocumentoentrante->tierdeid = $estado;
            $radicaciondocumentoentrante->save();

            $radicaciondocentcambioestado 					 = new RadicacionDocumentoEntranteCambioEstado();
            $radicaciondocentcambioestado->radoenid          = $codigo;
            $radicaciondocentcambioestado->tierdeid          = $estado;
            $radicaciondocentcambioestado->radeceusuaid      = Auth::id();
            $radicaciondocentcambioestado->radecefechahora   = $fechaHoraActual;
            $radicaciondocentcambioestado->radeceobservacion = $request->observacionCambio;
            $radicaciondocentcambioestado->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }
}
