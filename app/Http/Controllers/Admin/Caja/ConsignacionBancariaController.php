<?php

namespace App\Http\Controllers\Admin\Caja;

use App\Models\Caja\ComprobanteContableDetalle;
use App\Models\Caja\ConsignacionBancaria;
use App\Models\Caja\ComprobanteContable;
use App\Http\Controllers\Controller;
use App\Models\Caja\MovimientoCaja;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use Carbon\Carbon;

class ConsignacionBancariaController extends Controller
{
    public function index()
    {
        try{
            $consignacionBancarias = DB::table('consignacionbancaria as cb')
                                        ->select('cb.conbanid','cb.entfinid','cb.conbanfechahora','cb.conbanmonto','cb.conbandescripcion','ef.entfinnombre',
                                        DB::raw("FORMAT(cb.conbanmonto, 0) as monto"))
                                        ->join('entidadfinanciera as ef', 'ef.entfinid', '=', 'cb.entfinid')
                                        ->where('cb.usuaid', Auth::id())
                                        ->orderBy('cb.conbanid')->get();

            return response()->json(['success' => true, "data" => $consignacionBancarias]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function datosConsignacion()
    {
        try{
            $entidadFinancieras = DB::table('entidadfinanciera')->select('entfinid','entfinnombre')
                                        ->where('entfinactiva', true)
                                        ->orderBy('entfinnombre')->get();

            return response()->json(['success' => true, "entidadFinancieras" => $entidadFinancieras]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function salveConsignacion(Request $request)
	{
        $this->validate(request(),['entidadFinaciera' => 'required|numeric',
                                    'monto'           => 'required|numeric|between:1,999999999',
                                    'descripcion'     => 'required|string|min:10|max:200']);

        $cajaAbierta = MovimientoCaja::verificarCajaAbierta();
        if(!$cajaAbierta){
            return response()->json(['success' => false, 'message'=> 'Lo sentimos, no es posible registrar una consignación sin antes haber abierto la caja para el día de hoy']);
        }

        DB::beginTransaction();
        try {
            $fechaHoraActual = Carbon::now();
            $fechaActual     = $fechaHoraActual->format('Y-m-d');

            $consignacionbancaria                    =  new ConsignacionBancaria();
            $consignacionbancaria->entfinid          = $request->entidadFinaciera;
            $consignacionbancaria->usuaid            = Auth::id();
            $consignacionbancaria->agenid            = auth()->user()->agenid;
            $consignacionbancaria->conbanfechahora   = $fechaHoraActual;
            $consignacionbancaria->conbanmonto       = $request->monto;
            $consignacionbancaria->conbandescripcion = $request->descripcion;
            $consignacionbancaria->save();

            //Se realiza la contabilizacion
            $comprobanteContableId                       = ComprobanteContable::obtenerId($fechaActual);
            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 1;//Banco
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = $request->monto;
            $comprobantecontabledetalle->save();

            $comprobantecontabledetalle                  = new ComprobanteContableDetalle();
            $comprobantecontabledetalle->comconid        = $comprobanteContableId;
            $comprobantecontabledetalle->cueconid        = 2;//Caja;
            $comprobantecontabledetalle->cocodefechahora = $fechaHoraActual;
            $comprobantecontabledetalle->cocodemonto     = -$request->monto;//Lo ingresamos con movimiento crédito colocando un signo -
            $comprobantecontabledetalle->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro realizado con éxito' ]);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }
}