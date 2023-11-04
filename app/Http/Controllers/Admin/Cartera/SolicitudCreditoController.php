<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\SolicitudCreditoCambioEstado;
use App\Models\Cartera\SolicitudCredito;
use App\Http\Controllers\Controller;
use Exception, DB, Auth, URL;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Carbon\Carbon;

class SolicitudCreditoController extends Controller
{
    public function index()
    {
        $listaAsociados = DB::table('vehiculo as v')->select(DB::raw("CONCAT(a.asocid,'-', v.vehiid) as identificador"),
                        DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno,' ',p.persdocumento,' ', p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                        ->join('asociadovehiculo as av', 'av.vehiid', '=', 'v.vehiid')
                        ->join('asociado as a', 'a.asocid', '=', 'av.asocid')
                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                        ->where('v.tiesveid', 'A')
                        ->orderBy('v.vehinumerointerno')->get();
                      
        return response()->json(["listaAsociados" => $listaAsociados]);
    }

    public function consultar(Request $request)
    {
        $this->validate(request(),['asociadoId' => 'required|numeric' ]);

        try {
            $url      = URL::to('/');
            $asociado = DB::table('persona as p')->select('p.persdocumento',
                                    'p.persprimernombre','p.perssegundonombre','p.persprimerapellido','p.perssegundoapellido','p.persfechanacimiento',
                                    'p.persdireccion','p.perscorreoelectronico','p.persfechadexpedicion','p.persnumerotelefonofijo','p.persnumerocelular',
                                    'p.persgenero','p.persrutafoto','a.asocfechaingreso',
                                    DB::raw("CONCAT(ti.tipidesigla,' - ', ti.tipidenombre) as nombreTipoIdentificacion"),
                                    DB::raw("CONCAT('$url/archivos/persona/',p.persdocumento,'/',p.persrutafoto ) as fotografia"))
                                    ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                    ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                    ->where('a.asocid', $request->asociadoId)->first();

            $lineasCreditos = DB::table('lineacredito')
                                    ->select('lincreid','lincrenombre','lincretasanominal','lincremontominimo','lincremontomaximo', 'lincreplazomaximo')
                                    ->where('lincreactiva', true)->get();
        
			return response()->json(['success' => true, 'asociado' => $asociado, 'lineasCreditos' => $lineasCreditos]);
		} catch (Exception $error){		
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error al consultar => '.$error->getMessage()]);
		}
    }
    
    public function salve(Request $request)
	{
	    $this->validate(request(),[
                'asociadoId'         => 'required|numeric',
                'vehiculoId'         => 'required|numeric',
                'lineaCredito'       => 'required|numeric',
	   	        'destinoCredito'     => 'required|string|min:20|max:1000',
                'valorSolicitado'    => 'required|numeric|between:1,999999999',
				'tasaNominal'        => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'plazo'              => 'required|numeric|between:1,99',
				'observacionGeneral' => 'nullable|string|min:20|max:1000'
	        ]);

        DB::beginTransaction();
        try {
            $fechaHoraActual        = Carbon::now();
            $estadoSolicitudCredito = 'R'; //Registrado

            $solicitudcredito                        = new SolicitudCredito();
            $solicitudcredito->usuaid                = Auth::id();
            $solicitudcredito->lincreid              = $request->lineaCredito;
			$solicitudcredito->asocid                = $request->asociadoId;
            $solicitudcredito->vehiid                = $request->vehiculoId;
			$solicitudcredito->tiesscid              = $estadoSolicitudCredito;
			$solicitudcredito->solcrefechasolicitud  = $fechaHoraActual;
            $solicitudcredito->solcredescripcion     = $request->destinoCredito;
            $solicitudcredito->solcrevalorsolicitado = $request->valorSolicitado;
			$solicitudcredito->solcretasa            = $request->tasaNominal;
            $solicitudcredito->solcrenumerocuota     = $request->plazo;
            $solicitudcredito->solcreobservacion     = $request->observacionGeneral;
            $solicitudcredito->save();

            $solicitudCreditoMaxConsecutio = SolicitudCredito::latest('solcreid')->first();
            $solcreid                      = $solicitudCreditoMaxConsecutio->solcreid;

            $solicitudcreditocambioestado 					 = new SolicitudCreditoCambioEstado();
            $solicitudcreditocambioestado->solcreid          = $solcreid;
            $solicitudcreditocambioestado->tiesscid          = $estadoSolicitudCredito;
            $solicitudcreditocambioestado->socrceusuaid      = Auth::id();
            $solicitudcreditocambioestado->socrcefechahora   = $fechaHoraActual;
            $solicitudcreditocambioestado->socrceobservacion = 'Registro de la solicitud de credido. Proceso realizado por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
            $solicitudcreditocambioestado->save();

            DB::commit();
			return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito' ]);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function simular(Request $request)
	{
	    $this->validate(request(),[
                'asociadoId'         => 'required|numeric',
                'lineaCredito'       => 'required|numeric',
	   	        'destinoCredito'     => 'required|string|max:1000',
                'valorSolicitado'    => 'required|numeric|between:1,999999999',
				'tasaNominal'        => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'plazo'              => 'required|numeric|between:1,99'
	        ]);

        try {
            $lineasCredito = DB::table('lineacredito')->select('lincrenombre')->where('lincreid', $request->lineaCredito)->first();
            $asociado      = DB::table('persona as p')->select('a.asocid', DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                            ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                            ->where('a.asocid', $request->asociadoId)->first();

            $lineaCredito        = $lineasCredito->lincrenombre;
            $asociado            = $asociado->nombrePersona;
            $descripcionCredito  = $request->destinoCredito;
            $valorSolicitado     = $request->valorSolicitado;
            $tasaNominal         = $request->tasaNominal;
            $plazoMensual        = $request->plazo;

			$generarPdf    = new generarPdf();
			$dataDocumento = $generarPdf->simuladorCredito($lineaCredito, $asociado, $descripcionCredito, $valorSolicitado, $tasaNominal, $plazoMensual, 'S');
			return response()->json(["data" => $dataDocumento]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
   }   
}