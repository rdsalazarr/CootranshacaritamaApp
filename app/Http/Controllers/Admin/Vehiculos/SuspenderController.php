<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Vehiculos\VehiculoSuspendido;
use App\Console\Commands\FuncionesGenerales;
use App\Http\Controllers\Controller;
use App\Models\Vehiculos\Vehiculo;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;

class SuspenderController extends Controller
{
    public function index(Request $request)
    {
        $procesados = ($request->tipo === 'REGISTRADOS') ? false : true;

        $data = DB::table('vehiculosuspendido as vs')
                        ->select('vs.vehsusid', 'vs.vehiid', 'vs.vehsusfechainicialsuspencion','vs.vehsusfechafinalsuspencion','vs.vehsusmotivo',
                            DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"),
                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))
                        ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                        ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                        ->where('vs.vehsusprocesada', $procesados)
                        ->where('vs.usuaid', Auth::id())
                        ->orderBy('vs.vehsusfechahora', 'Desc')->get();

        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
    {
        $procesados = ($request->tipo === 'REGISTRADOS') ? false : true;

        $consulta = DB::table('vehiculo as v')
                            ->select('v.vehiid',DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
                            ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid');
                            if($request->tipo === 'I')
                                $consulta =  $consulta->where('v.tiesveid', 'A');

                            $data = $consulta->orderBy('v.vehinumerointerno')->get();

        $fechaActual   = Carbon::now()->format('Y-m-d');

        return response()->json(["data" => $data, "fechaActual" => $fechaActual]);
    }

    public function salve(Request $request)
	{
		$this->validate(request(),[ 'vehiculoId'             => 'required|numeric',
                                    'fechaInicialSuspencion' => 'required|date|date_format:Y-m-d',
                                    'fechaFinalSuspencion'   => 'nullable|date|date_format:Y-m-d',
                                    'motivo'                 => 'required|string|min:20|max:500',
                                    'codigo'                 => 'required',
                                    'tipo'                   => 'required',
                                ]);

		DB::beginTransaction();
        $estado                 = 'S';
        $fechaHoraActual        = Carbon::now();
        $mensajeNotificacion    = '';
        $fechaInicialFormateada = Carbon::parse($request->fechaInicialSuspencion);
		try {

            if($request->tipo === 'I' and $fechaInicialFormateada->isSameDay($fechaHoraActual)){
                $vehiculo           = Vehiculo::findOrFail($request->vehiculoId);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $request->vehiculoId;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = Auth::id();
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = mb_strtoupper($request->motivo,'UTF-8');
                $vehiculocambioestado->save();
            }

            if($request->tipo === 'I'){
                $vehiculosuspendido         = new VehiculoSuspendido();
                $vehiculosuspendido->vehiid = $request->vehiculoId;
                $vehiculosuspendido->usuaid = Auth::id();
            }else{
                $vehiculosuspendido         = VehiculoSuspendido::findOrFail($request->codigo);
            }
            $vehiculosuspendido->vehsusfechahora              = $fechaHoraActual;
            $vehiculosuspendido->vehsusfechainicialsuspencion = $request->fechaInicialSuspencion;
            $vehiculosuspendido->vehsusfechafinalsuspencion   = $request->fechaFinalSuspencion;
            $vehiculosuspendido->vehsusmotivo                 = mb_strtoupper($request->motivo,'UTF-8');
            $vehiculosuspendido->save();

            //notifico la informacion al asociado de la suspencion del vehiculo
            if($request->tipo === 'I'){

                $dataVehiculo = DB::table('vehiculo as v')
                            ->select('v.vehinumerointerno','v.vehiplaca','p.perscorreoelectronico',
                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado")) 
                            ->join('asociado as a', 'a.asocid', '=', 'v.asocid')
                            ->join('persona as p', 'p.persid', '=', 'a.persid')
                            ->where('vehiid', $request->vehiculoId)->first();

                $fechaActual         = $fechaHoraActual->format('Y-m-d');
                $nombreAsociado      = $dataVehiculo->nombreAsociado;
                $placaVehiculo       = $dataVehiculo->vehiplaca;
                $numeroInterno       = $dataVehiculo->vehinumerointerno;
                $correoUsuario       = $dataVehiculo->perscorreoelectronico;
                $motivosSuspencion   = mb_strtoupper($request->motivo,'UTF-8');
                $fechaInicial        = $request->fechaInicialSuspencion;
                $fechaFinal          = ($request->fechaFinalSuspencion !== '') ? $request->fechaFinalSuspencion : 'No definida';
                $empresa             = FuncionesGenerales::consultarInfoEmpresa();
                $correoEmpresa       = $empresa->emprcorreo;
                $nombreGerente       = $empresa->nombreGerente;
                $notificar           = new notificar();

                if($correoUsuario !== ''){
                    $informacionCorreo   = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'notificarSuspencionVehiculoUsuario')->first();
                    $buscar              = Array('fechaActual', 'nombreAsociado', 'placaVehiculo', 'numeroInterno', 'motivosSuspencion', 'fechaInicial', 'fechaFinal', 'nombreGerente');
                    $remplazo            = Array($fechaActual, $nombreAsociado, $placaVehiculo, $numeroInterno, $motivosSuspencion, $fechaInicial, $fechaFinal, $nombreGerente); 
                    $innocoasunto        = $informacionCorreo->innocoasunto;
                    $innococontenido     = $informacionCorreo->innococontenido;
                    $enviarcopia         = $informacionCorreo->innocoenviarcopia;
                    $enviarpiepagina     = $informacionCorreo->innocoenviarpiepagina;
                    $asunto              = str_replace($buscar, $remplazo, $innocoasunto);
                    $msg                 = str_replace($buscar, $remplazo, $innococontenido);
                    $mensajeNotificacion = ', se ha enviado notificación a '.$notificar->correo([$correoUsuario], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                }
            }

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito '.$mensajeNotificacion]);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}