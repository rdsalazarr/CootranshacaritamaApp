<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\EncomiendaCambioEstado;
use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ComprobanteContable;
use App\Models\Despacho\PlanillaRuta;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use App\Models\Caja\CuentaContable;
use App\Models\Despacho\Encomienda;
use Illuminate\Http\Request;
use Exception, DB, Auth;
use Carbon\Carbon;

class RecibirPlanillaRutaController extends Controller
{
    public function index()
    {
        $agencias             = DB::table('agencia')->select('agenid','agennombre')->orderBy('agennombre', 'Desc')->get();
        $tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->whereIn('tipideid', ['1','4', '5'])->orderBy('tipidenombre')->get();
        $anyos                = DB::table('planillaruta')->distinct()->select('plarutanio')->get();

        return response()->json(["agencias" => $agencias, "tipoIdentificaciones" => $tipoIdentificaciones, "anyos" => $anyos]);
    }

    public function salve(Request $request)
	{
        $this->validate(request(),['agencia' => 'required|numeric', 'anyo' => 'required|numeric', 'consecutivo' => 'required|numeric']);

        $consecutivo  = str_pad($request->consecutivo,  4, "0", STR_PAD_LEFT);
        $planillaruta = DB::table('planillaruta')->select('plarutid')
                                            ->where('agenid', $request->agencia)
                                            ->where('plarutanio', $request->anyo)
                                            ->where('plarutconsecutivo', $consecutivo)
                                            ->whereNull('plarutfechallegadaaldestino')
                                            ->where('plarutdespachada', true)->first();
        if(!$planillaruta){
            return response()->json(['success' => false, 'message'=> 'La información proporcionada no generó resultados para mostrar']); 
        }

        DB::beginTransaction();
        try {
            $fechaHoraActual                           = Carbon::now();
            $planillaruta                              = PlanillaRuta::findOrFail($planillaruta->plarutid); 
            $planillaruta->plarutfechallegadaaldestino = $fechaHoraActual; 
           	$planillaruta->save();

            $encomiendas  = DB::table('encomienda')->select('encoid')->where('plarutid', $planillaruta->plarutid)->get();

            foreach($encomiendas as $encomienda){

                $encomienda           = Encomienda::findOrFail($encomienda->encoid);
                $encomienda->tiesenid = 'D';
                $encomienda->save();

                $encomiendacambioestado 				   = new EncomiendaCambioEstado();
                $encomiendacambioestado->encoid            = $encomienda->encoid;
                $encomiendacambioestado->tiesenid          = 'D';
                $encomiendacambioestado->encaesusuaid      = Auth::id();
                $encomiendacambioestado->encaesfechahora   = $fechaHoraActual;
                $encomiendacambioestado->encaesobservacion = 'En terminal destino. Proceso realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $encomiendacambioestado->save();
            }

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function consultarPersona(Request $request)
	{
        $this->validate(request(),['tipoIdentificacion' => 'required|numeric','documento' => 'required|string|max:15', 'tipoPersona' => 'required']);

        $message   = 'La búsqueda con los criterios proporcionados no arrojó resultados. Es posible que la encomienda no esté disponible en el terminal de destino';
        $consulta  = DB::table('encomienda as e')
                        ->select('e.encoid','e.tiesenid','e.encofechahoraregistro as fechaHoraRegistro', 'te.tipencnombre as tipoEncomienda',
                        DB::raw("CONCAT(FORMAT(e.encovalortotal, 0)) as valorTotalEncomienda"),
                        DB::raw("CONCAT(de.depanombre,' - ',md.muninombre) as destinoEncomienda"), DB::raw("if(e.encopagocontraentrega = 1 ,'SÍ', 'NO') as pagoContraEntrega"),
                        DB::raw("CONCAT(pr.agenid, '-', pr.plarutconsecutivo,' - ', mor.muninombre,' - ', mdr.muninombre) as nombreRuta"),
                        DB::raw("CONCAT(ps.perserprimernombre,' ',if(ps.persersegundonombre is null ,'', ps.persersegundonombre),' ',
                            ps.perserprimerapellido,' ',if(ps.persersegundoapellido is null ,' ', ps.persersegundoapellido)) as nombrePersonaRemitente"),
                        DB::raw("CONCAT(ps1.perserprimernombre,' ',if(ps1.persersegundonombre is null ,'', ps1.persersegundonombre),' ',
                                ps1.perserprimerapellido,' ',if(ps1.persersegundoapellido is null ,' ', ps1.persersegundoapellido)) as nombrePersonaDestino"))
                        ->join('personaservicio as ps', 'ps.perserid', '=', 'e.perseridremitente')
                        ->join('personaservicio as ps1', 'ps1.perserid', '=', 'e.perseriddestino')
                        ->join('tipoencomienda as te', 'te.tipencid', '=', 'e.tipencid')
                        ->join('departamento as de', 'de.depaid', '=', 'e.encodepaiddestino') 
                        ->join('municipio as md', function($join)
                        {
                            $join->on('md.munidepaid', '=', 'e.encodepaiddestino');
                            $join->on('md.muniid', '=', 'e.encomuniiddestino');
                        })
                        ->join('planillaruta as pr', 'pr.plarutid', '=', 'e.plarutid')
                        ->join('ruta as r', 'r.rutaid', '=', 'pr.rutaid')
                        ->join('municipio as mor', function($join)
                        {
                            $join->on('mor.munidepaid', '=', 'r.rutadepaidorigen');
                            $join->on('mor.muniid', '=', 'r.rutamuniidorigen');
                        })
                        ->join('municipio as mdr', function($join)
                        {
                            $join->on('mdr.munidepaid', '=', 'r.rutadepaiddestino');
                            $join->on('mdr.muniid', '=', 'r.rutamuniiddestino');
                        });

                        if($request->tipoPersona === 'R')
                            $consulta = $consulta->where('ps.tipideid', $request->tipoIdentificacion)
                                                 ->where('ps.perserdocumento', $request->documento);

                        if($request->tipoPersona === 'D')
                            $consulta = $consulta->where('ps1.tipideid', $request->tipoIdentificacion)
                                                 ->where('ps1.perserdocumento', $request->documento);
  
                $data = $consulta->where('e.tiesenid', 'D')->get();

        return response()->json(['success' => (count($data) > 0) ? true : false, 'data' => $data, 'message' => $message]);
    }

    public function salveEntregaEncomienda(Request $request)
	{
        $this->validate(request(),['codigo' => 'required|numeric']);

        $consecutivo                = str_pad($request->consecutivo,  4, "0", STR_PAD_LEFT);
        $encomiendaTerminalDestino  = DB::table('encomienda')->select('encoid','encopagocontraentrega')
                                            ->where('encoid', $request->codigo)
                                            ->where('tiesenid', 'D')->first();
        if(!$encomiendaTerminalDestino){
            return response()->json(['success' => false, 'message'=> 'Los datos proporcionados no coinciden. Favor de verificar la información nuevamente']); 
        }

        //Verifico que tenga una caja abierta
        $cajaAbierta = MovimientoCaja::verificarCajaAbierta();
        if($encomiendaTerminalDestino->encopagocontraentrega && !$cajaAbierta){
            return response()->json(['success' => false, 'message'=> 'Lo sentimos, no es posible entregar una encomienda contraentrega, sin antes haber abierto la caja para el día de hoy']); 
        }

        DB::beginTransaction();
        try {

            $dataFactura                   = '';
            $fechaHoraActual               = Carbon::now();
            $fechaActual                   = $fechaHoraActual->format('Y-m-d');
            $encomienda                    = Encomienda::findOrFail($request->codigo);
            $encomienda->tiesenid          = 'E';
            ($encomienda->encopagocontraentrega) ? $encomienda->encocontabilizada = true : '';
            $encomienda->save();

            $encomiendacambioestado 				   = new EncomiendaCambioEstado();
            $encomiendacambioestado->encoid            = $encomienda->encoid;
            $encomiendacambioestado->tiesenid          = 'E';
            $encomiendacambioestado->encaesusuaid      = Auth::id();
            $encomiendacambioestado->encaesfechahora   = $fechaHoraActual;
            $encomiendacambioestado->encaesobservacion = 'La encomienda ha sido entregada. Proceso realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
            $encomiendacambioestado->save();

            if($encomienda->encopagocontraentrega){
                //Se realiza la contabilizacion
                $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
                $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('caja');
                $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                $comprobantecontabledetalle->cocodemonto     = $encomienda->encovalortotal;
                $comprobantecontabledetalle->save();

                $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
                $comprobantecontabledetalle->comconid        = $comprobanteContableId;
                $comprobantecontabledetalle->cueconid        = CuentaContable::consultarId('pagoEncomiendaContraentrega');
                $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
                $comprobantecontabledetalle->cocodemonto     = $encomienda->encovalortotal;
                $comprobantecontabledetalle->save();
            }

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito', 'dataFactura' => $dataFactura]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }
}