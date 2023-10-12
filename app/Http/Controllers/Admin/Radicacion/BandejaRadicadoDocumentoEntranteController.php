<?php

namespace App\Http\Controllers\Admin\Radicacion;

use App\Models\RadicacionDocumentoEntranteCambioEstado;
use App\Models\RadicacionDocumentoEntranteDependencia;
use App\Models\RadicacionDocumentoEntrante;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon;

class BandejaRadicadoDocumentoEntranteController extends Controller
{
    public function index(Request $request)
	{
		$this->validate(request(),['tipo' => 'required']);

        try {
            $consulta   = DB::table('radicaciondocumentoentrante as rde')
                        ->select('rde.radoenid as id', 'rded.radoedid as idFirma', 'rde.tierdeid', 'rde.radoenfechahoraradicado as fechaRadicado','rde.radoenasunto as asunto',
                            DB::raw("CONCAT(rde.radoenanio,' - ', rde.radoenconsecutivo) as consecutivo"),'d.depenombre as dependencia','terde.tierdenombre as estado',
                            DB::raw("CONCAT(prd.peradoprimernombre,' ',if(prd.peradosegundonombre is null ,'', prd.peradosegundonombre),' ', prd.peradoprimerapellido,' ',if(prd.peradosegundoapellido is null ,' ', prd.peradosegundoapellido)) as nombrePersonaRadica"))                    
                        ->join('tipoestadoraddocentrante as terde', 'terde.tierdeid', '=', 'rde.tierdeid')
                        ->join('personaradicadocumento as prd', 'prd.peradoid', '=', 'rde.peradoid')
                        ->join('radicaciondocentdependencia as rded', function($join)
                            {
                                $join->on('rded.radoenid', '=', 'rde.radoenid');
                                $join->where('rded.radoedescopia', false); 
                            })
                        ->join('dependencia as d', 'd.depeid', '=', 'rded.depeid')
                        ->whereIn('rded.depeid', function($query) {
                                $query->select('depperdepeid')->from('dependenciapersona')
                                        ->where('depperpersid',  auth()->user()->persid);
                                });

                    if($request->tipo === 'VERIFICADOS')
                        $consulta = $consulta->where('rde.tierdeid', 2);

                    if($request->tipo === 'COPIAS'){
                        $consulta = $consulta->join('radicaciondocentdependencia as rded1', function($join)
                                                    {
                                                        $join->on('rded1.radoenid', '=', 'rde.radoenid');
                                                        $join->where('rded1.radoedescopia', true); 
                                                    })
                                                ->whereIn('rde.tierdeid', [1,2,3,4]);
                    }

                    if($request->tipo === 'RECIBIDOS')
                        $consulta = $consulta->where('rde.tierdeid', '!=', 2);

                $data = $consulta->orderBy('rde.radoenid', 'Desc')->get();

            return response()->json(['success' => true, "data" => $data]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$error->getMessage()]);
        }
    }

    public function recibir(Request $request)
	{
        $this->validate(request(),['id'                => 'required|numeric', 
                                   'idFirma'           => 'required|numeric', 
                                   'requiereRespuesta' => 'required|numeric']);

        $estado          = '3';
        $codigo          = $request->id;
        $fechaHoraActual = Carbon::now();
        $dataCopias      = [];
        DB::beginTransaction();
        try {

            $radicaciondocumentoentrante                          = RadicacionDocumentoEntrante::findOrFail($codigo);
            $radicaciondocumentoentrante->tierdeid                = $estado;
            $radicaciondocumentoentrante->radoenrequiererespuesta = $request->requiereRespuesta;
            $radicaciondocumentoentrante->save();

            $coddocumprocesocopia                          = RadicacionDocumentoEntranteDependencia::findOrFail($request->idFirma);
            $coddocumprocesocopia->radoedsuaid             = Auth::id();
            $coddocumprocesocopia->radoedfechahorarecibido = $fechaHoraActual;
            $coddocumprocesocopia->save();

            $radicaciondocentcambioestado 					 = new RadicacionDocumentoEntranteCambioEstado();
            $radicaciondocentcambioestado->radoenid          = $codigo;
            $radicaciondocentcambioestado->tierdeid          = $estado;
            $radicaciondocentcambioestado->radeceusuaid      = Auth::id();
            $radicaciondocentcambioestado->radecefechahora   = $fechaHoraActual;
            $radicaciondocentcambioestado->radeceobservacion = 'Documento recibido por '.auth()->user()->usuanombre.'  en la fecha '.$fechaHoraActual;
            $radicaciondocentcambioestado->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }
}