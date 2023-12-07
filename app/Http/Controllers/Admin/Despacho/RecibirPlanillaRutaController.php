<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\EncomiendaCambioEstado;
use App\Models\Despacho\PlanillaRuta;
use App\Http\Controllers\Controller;
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

    public function salveEntregaEncomienda(Request $request)
	{
        $this->validate(request(),['agencia' => 'required|numeric', 'anyo' => 'required|numeric', 'consecutivo' => 'required|numeric' ]);

        $consecutivo = str_pad($request->consecutivo,  4, "0", STR_PAD_LEFT);
        $encomienda  = DB::table('encomienda')->select('encoid')
                                            ->where('agenid', $request->agencia)
                                            ->where('encoanio', $request->anyo)
                                            ->where('encoconsecutivo', $consecutivo)
                                            ->where('tiesenid', 'D')->first();
        if(!$encomienda){
            return response()->json(['success' => false, 'message'=> 'La información proporcionada no generó resultados para mostrar']); 
        }

        DB::beginTransaction();
        try {

            $fechaHoraActual      = Carbon::now();
            $encomienda           = Encomienda::findOrFail($encomienda->encoid);
            $encomienda->tiesenid = 'E';
            $encomienda->save();

            $encomiendacambioestado 				   = new EncomiendaCambioEstado();
            $encomiendacambioestado->encoid            = $encomienda->encoid;
            $encomiendacambioestado->tiesenid          = 'E';
            $encomiendacambioestado->encaesusuaid      = Auth::id();
            $encomiendacambioestado->encaesfechahora   = $fechaHoraActual;
            $encomiendacambioestado->encaesobservacion = 'La encomienda ha sido entregada. Proceso realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
            $encomiendacambioestado->save();

            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }
}