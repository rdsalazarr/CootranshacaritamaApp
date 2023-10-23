<?php

namespace App\Http\Controllers\Admin\Asociado;

use App\Http\Controllers\Controller;
use App\Models\Asociado\AsociadoCambioEstado;
use App\Models\Asociado\Asociado;
use Illuminate\Http\Request;
use Exception, DB, URL;

class DesvincularAsociadoController extends Controller
{
    public function index(Request $request)
    { 
        $this->validate(request(),[ 'tipoIdentificacion' => 'required|numeric',
									'documento' 		 => 'required|string|max:15'
                                ]);

        $url        = URL::to('/');
        $persona    = DB::table('persona as p')->select('a.asocid', 'p.persid','p.carlabid','p.tipideid','p.tirelaid','p.persdepaidnacimiento','p.persmuniidnacimiento',
                                'p.persdepaidexpedicion','p.persmuniidexpedicion','p.persdocumento','p.perstienefirmadigital',
                                'p.persprimernombre','p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                'p.persgenero','p.persrutafoto','p.persrutafirma','p.persactiva','p.persrutapem','p.persrutacrt','p.persclavecertificado',
                                DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"),
                                DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafirma ) as firmaPersona"),
                                DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutacrt ) as rutaCrt"),
                                DB::raw("CONCAT('/download/certificado/',p.persdocumento,'/',p.persrutapem ) as rutaPem"),
                                'a.asocfechaingreso as fechaIngresoAsocido', 'a.asocfechaingreso as fechaIngresoConductor')
                                ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                ->where('p.tipideid', $request->tipoIdentificacion)
                                ->where('p.persdocumento', $request->documento)
                                ->first();

        $tipoestadoasociados   = DB::table('tipoestadoasociado')->select('tiesasid','tiesasnombre')->orderBy('tiesasnombre')->get();

        $array = ($persona !== null) ? ['success' => true, "data" => $persona , "tipoestadoasociados" => $tipoestadoasociados] : 
                                      ['success' => false, "message" => 'No se encontraron resultados que coincidan con los criterios de búsqueda seleccionados'];

        return response()->json($array);
    }

    public function desvincular(Request $request)
	{ 
		$this->validate(request(),[ 'tipoIdentificacion' => 'required|numeric',
									'documento' 		 => 'required|string|max:15',
                                    'asociadoId'         => 'required|numeric', 
                                    'estado' 		     => 'required|string|max:2',
                                    'observacionCambio'  => 'required|string|min:20|max:500'
                                ]);

		DB::beginTransaction();
		try {
            $fechaHoraActual            = Carbon::now();
            $fechaActual                = Carbon::now()->format('Y-m-d');
			$asociadoId                 = $request->asociadoId;
            $estado                     = $request->asociadoId;
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