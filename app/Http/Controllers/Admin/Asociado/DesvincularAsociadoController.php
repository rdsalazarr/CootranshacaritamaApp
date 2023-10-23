<?php

namespace App\Http\Controllers\Admin\Asociado;

use App\Http\Controllers\Controller;
use App\Models\Asociado\AsociadoCambioEstado;
use App\Models\Asociado\Asociado;
use Illuminate\Http\Request;
use Exception, Auth, DB;
use Carbon\Carbon;

class DesvincularAsociadoController extends Controller
{
    public function index()
    {
        $tipoEstadosAsociados = DB::table('tipoestadoasociado')->select('tiesasid','tiesasnombre')->orderBy('tiesasnombre')->get();
        $tipoIdentificaciones = DB::table('tipoidentificacion')->select('tipideid','tipidenombre')->orderBy('tipidenombre')->get();

        return response()->json(["tipoIdentificaciones" => $tipoIdentificaciones , "tipoEstadosAsociados" => $tipoEstadosAsociados]);
    }

    public function consultar(Request $request)
    { 
        $this->validate(request(),[ 'tipoIdentificacion' => 'required|numeric',
									'documento' 		 => 'required|string|max:15'
                                ]);

        $persona    = DB::table('persona as p')->select('a.asocid', 'a.persid','a.tiesasid')
                                ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                ->where('p.tipideid', $request->tipoIdentificacion)
                                ->where('p.persdocumento', $request->documento)
                                ->first();

        $array = ($persona !== null) ? ['success' => true, "data" => $persona ] :
                                      ['success' => false, "message" => 'No se encontraron resultados que coincidan con los criterios de búsqueda ingresados'];

        return response()->json($array);
    }

    public function desvincular(Request $request)
	{ 
		$this->validate(request(),[ 'tipoIdentificacion' => 'required|numeric',
									'documento' 		 => 'required|string|max:15',
                                    'asociadoId'         => 'required|numeric', 
                                    'tipoEstado' 	     => 'required|string|max:2',
                                    'observacionCambio'  => 'required|string|min:20|max:500'
                                ]);

		DB::beginTransaction();
		try {
            $fechaHoraActual            = Carbon::now();
            $fechaActual                = Carbon::now()->format('Y-m-d');
			$asociadoId                 = $request->asociadoId;
            $estado                     = $request->tipoEstado;
            $asociado                   = Asociado::findOrFail($asociadoId);
            $asociado->tiesasid         = $estado;
            $asociado->asocfecharetiro  = $fechaActual;
            $asociado->save();

            $asociadocambioestado 					 = new AsociadoCambioEstado();
			$asociadocambioestado->asocid            = $asociadoId;
			$asociadocambioestado->tiesasid          = $estado;
			$asociadocambioestado->ascaesusuaid      = Auth::id();
			$asociadocambioestado->ascaesfechahora   = $fechaHoraActual;
			$asociadocambioestado->ascaesobservacion = $request->observacionCambio;
			$asociadocambioestado->save();

			DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}