<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\VehiculoResponsabilidad;
use App\Models\Vehiculos\VehiculoContratoFirma;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Vehiculos\VehiculoContrato;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

use Exception, Auth, File, DB, URL;
use App\Models\Vehiculos\Vehiculo;
use App\Util\redimencionarImagen;
use Illuminate\Http\Request;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;

class VehiculoController extends Controller
{
    public function index()
    {
        $data = DB::table('vehiculo as v')->select('v.vehiid','v.vehifechaingreso','v.vehinumerointerno','v.vehiplaca','v.vehimodelo','v.vehicilindraje',
                                                    'v.vehinumeromotor','v.vehinumerochasis','v.vehinumeroserie','v.vehinumeroejes','tv.tipvehnombre', 'tev.tiesvenombre as estado')
                                                    ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                                    ->join('tipoestadovehiculo as tev', 'tev.tiesveid', '=', 'v.tiesveid')
                                                    ->orderBy('v.vehiplaca')->get();
        return response()->json(["data" => $data]);
    }
    
	public function datos(Request $request)
	{
		$this->validate(request(),['codigo' => 'required','tipo' => 'required']);

        $tipoPeticion               = ($request->tipo === 'I') ? true : false;
        $consultaTipoVehiculo       = DB::table('tipovehiculo')->select('tipvehid','tipvehnombre','tipvehreferencia');
                                        if($tipoPeticion)
                                            $consultaTipoVehiculo = $consultaTipoVehiculo->where('tipvehactivo', true);
        $tipovehiculos              = $consultaTipoVehiculo->orderBy('tipvehnombre')->orderBy('tipvehreferencia')->get();

        $consultaTipoReferencia     = DB::table('tiporeferenciavehiculo')->select('tireveid','tirevenombre');
                                        if($tipoPeticion)
                                            $consultaTipoReferencia = $consultaTipoReferencia->where('tireveactivo', true);
        $tiporeferenciavehiculos    = $consultaTipoReferencia->orderBy('tirevenombre')->get();

        $consultaTipoMarca          = DB::table('tipomarcavehiculo')->select('timaveid','timavenombre');
                                        if($tipoPeticion)
                                            $consultaTipoMarca = $consultaTipoMarca->where('timaveactiva', true);
        $tipomarcavehiculos         = $consultaTipoMarca->orderBy('timavenombre')->get();

        $consultaTipoCarroceria     = DB::table('tipocarroceriavehiculo')->select('ticaveid','ticavenombre');
                                        if($tipoPeticion)
                                            $consultaTipoCarroceria = $consultaTipoCarroceria->where('ticaveactivo', true);
        $tipocarroceriavehiculos    = $consultaTipoCarroceria->orderBy('ticavenombre')->get();

        $consultaTipoColor          = DB::table('tipocolorvehiculo')->select('ticoveid','ticovenombre');
                                        if($tipoPeticion)
                                            $consultaTipoColor = $consultaTipoColor->where('ticoveactivo', true);
        $tipocolorvehiculos         = $consultaTipoColor->orderBy('ticovenombre')->get();

        $consultaAgencia            = DB::table('agencia')->select('agenid','agennombre');
                                        if($tipoPeticion)
                                            $consultaAgencia = $consultaAgencia->where('agenactiva', true);
        $agencias                   = $consultaAgencia->orderBy('agennombre')->get();

        $tipocombustiblevehiculos   = DB::table('tipocombustiblevehiculo')->select('ticovhid','ticovhnombre')->orderBy('ticovhnombre')->get();
        $tipomodalidadvehiculos     = DB::table('tipomodalidadvehiculo')->select('timoveid','timovenombre')->orderBy('timovenombre')->get();
        $asociados                  = DB::table('persona as p')->select('a.asocid', 'p.persid', DB::raw("if(p.perstienefirmaelectronica = 1 ,'SI', 'NO') as tieneFirmaElectronica"),
                                            DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombrePersona"))
                                            ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                            ->where('a.tiesasid', 'A')
                                            ->orderBy('nombrePersona')->get();

		$vehiculo         = [];
		if($request->tipo === 'U'){
            $url      = URL::to('/');
			$vehiculo = DB::table('vehiculo')
                            ->select('asocid','vehiid','tipvehid','tireveid','timaveid','ticoveid','timoveid','ticaveid','ticovhid','agenid',
                                    'tiesveid','vehifechaingreso','vehinumerointerno','vehiplaca','vehimodelo','vehicilindraje',
                                    'vehinumeromotor','vehinumerochasis','vehinumeroserie','vehinumeroejes','vehiesmotorregrabado',
                                    'vehieschasisregrabado','vehiesserieregrabado','vehirutafoto','vehiobservacion',
                                    DB::raw("CONCAT('$url/archivos/vehiculo/', vehiplaca, '/', vehirutafoto ) as rutaFotografia"))
                            ->where('vehiid', $request->codigo)->first();
		}

        return response()->json(['success' => true, 'tipovehiculos' => $tipovehiculos,   'tiporeferenciavehiculos'  => $tiporeferenciavehiculos, 
                                'tipomarcavehiculos'       => $tipomarcavehiculos,       'tipocarroceriavehiculos'  => $tipocarroceriavehiculos,
                                'tipocolorvehiculos'       => $tipocolorvehiculos,       'agencias'                 => $agencias,
                                'tipocombustiblevehiculos' => $tipocombustiblevehiculos, 'tipomodalidadvehiculos'   => $tipomodalidadvehiculos,
                                'vehiculo' => $vehiculo ,                                'asociados'                => $asociados]);
	}

    public function salve(Request $request)
	{
        $vehiid   = $request->codigo;
        $vehiculo = ($vehiid != 000) ? Vehiculo::findOrFail($vehiid) : new Vehiculo();

	    $this->validate(request(),[
                'asociado'              => 'required|numeric',
                'tipoVehiculo'          => 'required|numeric',
                'tipoReferencia'        => 'required|numeric',
                'tipoMarca'             => 'required|numeric',
                'tipoCombustible'       => 'required|numeric',
                'tipoModalidad'         => 'required',
                'tipoCarroceria'        => 'required|numeric',
                'tipoColor'             => 'required|numeric',
                'agencia'               => 'required|numeric',
                'fechaIngreso' 	        => 'required|date|date_format:Y-m-d',
                'numeroInterno'         => 'required|numeric|min:1|max:9999',
                'placa'                 => 'required|string|min:4|max:8|unique:vehiculo,vehiplaca,'.$vehiculo->vehiid.',vehiid',
                'modelo'                => 'required|numeric|min:1|max:9999',
                'cilindraje'            => 'nullable|string|min:1|max:6',
                'numeroMotor'           => 'nullable|string|max:30',
                'numeroChasis'          => 'nullable|string|max:30',
                'numeroSerie'           => 'nullable|string|max:30',
                'numeroEjes'            => 'nullable|numeric|min:1|max:9999',
                'motorRegrabado'        => 'required|numeric',
                'chasisRegrabado'       => 'required|numeric',
                'serieRegrabado'        => 'required|numeric',
                'observacion'           => 'nullable|string|max:500',
                'fotografia'            => 'nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG|max:1000',
                'fechaInicialContrato'  => 'nullable|date_format:Y-m-d|required_if:tipo,I',
                'firmaElectronia'       => 'required'                
	        ]);

        DB::beginTransaction();
        try {

            $estado              = 'A';
            $redimencionarImagen = new redimencionarImagen();
            $funcion 		     = new generales();
            $rutaCarpeta         = public_path().'/archivos/vehiculo/'.$request->placa;
            $carpetaServe        = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            if($request->hasFile('fotografia')){
				$file           = $request->file('fotografia');
				$nombreOriginal = $file->getclientOriginalName();
				$filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
				$extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
				$rutaFotografia = $request->numeroInterno."_".$funcion->quitarCaracteres($filename).'.'.$extension;
				$file->move($rutaCarpeta, $rutaFotografia);
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaFotografia, 200, 110);//Se redimenciona a un solo tipo
			}else{
				$rutaFotografia = $request->rutaFotoOld;
			}

            $vehiculo->asocid                = $request->asociado;
            $vehiculo->tipvehid              = $request->tipoVehiculo;
            $vehiculo->tireveid              = $request->tipoReferencia;
            $vehiculo->timaveid              = $request->tipoMarca;
            $vehiculo->ticoveid              = $request->tipoColor;
            $vehiculo->timoveid              = $request->tipoModalidad;
            $vehiculo->ticaveid              = $request->tipoCarroceria;
            $vehiculo->ticovhid              = $request->tipoCombustible;
            $vehiculo->agenid                = $request->agencia;
            $vehiculo->tiesveid              = $estado;
            $vehiculo->vehifechaingreso      = $request->fechaIngreso;
            $vehiculo->vehinumerointerno     = $request->numeroInterno;
            $vehiculo->vehiplaca             = mb_strtoupper($request->placa,'UTF-8');
            $vehiculo->vehimodelo            = $request->modelo;
            $vehiculo->vehicilindraje        = $request->cilindraje;
            $vehiculo->vehinumeromotor       = $request->numeroMotor;
            $vehiculo->vehinumerochasis      = $request->numeroChasis;
            $vehiculo->vehinumeroserie       = $request->numeroSerie;
            $vehiculo->vehinumeroejes        = $request->numeroEjes;
            $vehiculo->vehiesmotorregrabado  = $request->motorRegrabado;
            $vehiculo->vehieschasisregrabado = $request->chasisRegrabado;
            $vehiculo->vehiesserieregrabado  = $request->serieRegrabado;
            $vehiculo->vehiobservacion       = $request->observacion;
            $vehiculo->vehirutafoto          = $rutaFotografia;
            $vehiculo->save();

            if($request->tipo === 'I'){
                $representante               =  DB::table('empresa as e')->select('e.emprcorreo','p.persid','p.perscorreoelectronico',
                                                DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreGerente"))
                                                ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                                ->where('emprid', '1')->first();
                
                $correoEmpresa                = $representante->emprcorreo;
                $nombreGerente                = $representante->nombreGerente;
                $correoGerente                = $representante->perscorreoelectronico;
                $fechaHoraActual              = Carbon::now();
                $anioActual                   = $fechaHoraActual->year;
                $fechaInicialContrato         = Carbon::parse($request->fechaInicialContrato);
                $fechaInicialContratoAdiciona = $fechaInicialContrato->endOfYear();
                $fechaFinalContrato           = $fechaInicialContratoAdiciona->toDateString();	
                $vehiculoMaxConsecutio        = Vehiculo::latest('vehiid')->first();
                $vehiid                       = $vehiculoMaxConsecutio->vehiid;
                $numeroContrato               = $this->obtenerConsecutivoContrato($anioActual);

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $vehiid;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = Auth::id();
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = 'Registro del vehículo. Este procedimiento fue llevado a cabo por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $vehiculocambioestado->save();

                $vehiculocontrato                     = new VehiculoContrato();
                $vehiculocontrato->asocid             = $request->asociado;
                $vehiculocontrato->vehiid             = $vehiid;
                $vehiculocontrato->persidgerente      = $representante->persid;
                $vehiculocontrato->vehconanio         = $anioActual;
                $vehiculocontrato->vehconnumero       = $numeroContrato;
                $vehiculocontrato->vehconfechainicial = $request->fechaInicialContrato;
                $vehiculocontrato->vehconfechafinal   = $fechaFinalContrato;
                $vehiculocontrato->vehconobservacion  = 'Se ha generado el contrato del vehículo por primera vez. Este procedimiento fue llevado a cabo por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $vehiculocontrato->save();

                $vehiculoContratoMaxConsecutio = VehiculoContrato::latest('vehconid')->first();
                $vehconid                      = $vehiculoContratoMaxConsecutio->vehconid;


                $tipoModalidadVehiculo   = DB::table('tipomodalidadvehiculo')->select('timovecuotasostenimiento')->where('timoveid', $request->tipoModalidad)->first();
                $fechasCompromisos       = $funcion->obtenerFechasCompromisoVehiculo($request->fechaInicialContrato);
                $valorMensualidadInicial = $funcion->obtenerPrimerValorMensualidad($request->fechaInicialContrato, $tipoModalidadVehiculo->timovecuotasostenimiento);
                $valorCuotaSostenimiento = $tipoModalidadVehiculo->timovecuotasostenimiento;
                foreach($fechasCompromisos as $id => $fechaCompromiso){
                    $vehiculoresponsabilidad                             = new VehiculoResponsabilidad();
                    $vehiculoresponsabilidad->vehiid                     = $vehiid;
                    $vehiculoresponsabilidad->vehresfechacompromiso      = $fechaCompromiso;
                    $vehiculoresponsabilidad->vehresvalorresponsabilidad = ($id === 0 && $valorMensualidadInicial > 0 ) ? $valorMensualidadInicial : $valorCuotaSostenimiento;
                    $vehiculoresponsabilidad->save();
                }
                
                //Creamos la firma de los contratos
                if($request->firmaElectronia === 'SI'){
                    $vehiculocontratofirma = new VehiculoContratoFirma();
                    $vehiculocontratofirma->vehconid = $vehconid;
                    $vehiculocontratofirma->persid   = $representante->persid;
                    $vehiculocontratofirma->save();

                    //firma del asociado
                    $vehiculocontratofirma = new VehiculoContratoFirma();
                    $vehiculocontratofirma->vehconid = $vehconid;
                    $vehiculocontratofirma->persid   = $request->personaId;
                    $vehiculocontratofirma->save();

                    //Obtengo el id del contrato
                    $contratoMaxConsecutio = VehiculoContratoFirma::latest('vecofiid')->first();
                    $vecofiid              = $contratoMaxConsecutio->vecofiid;
                    $nombreUsuario         = auth()->user()->usuanombre.' '.auth()->user()->usuaapellidos;
                    $persona               = DB::table('persona')
                                            ->select('perscorreoelectronico',DB::raw("CONCAT(persprimernombre,' ',IFNULL(perssegundonombre,''),' ',persprimerapellido,' ',IFNULL(perssegundoapellido,'')) as nombreAsociado"))                                     
                                            ->where('persid', $request->personaId)->first();
                    $correoAsociado        = $persona->perscorreoelectronico;
                    $nombreAsociado        = $persona->nombreAsociado;
                    $urlFirmaContrato      = asset('firmar/contrato/asociado/'.Crypt::encrypt($vehconid).'/'.Crypt::encrypt($vecofiid));

                    //Notificamos a al gerente y al asociado
                    $notificar         = new notificar();
                    $informacioncorreos = DB::table('informacionnotificacioncorreo')->whereIn('innoconombre', ['solicitaFirmaContratoGerente', 'solicitaFirmaContratoAsociado'])->get();
                    foreach($informacioncorreos as $informacioncorreo){
                        $buscar             = Array('numeroContrato', 'nombreGerente', 'nombreUsuario', 'nombreAsociado', 'urlFirmaContrato');
                        $remplazo           = Array($numeroContrato, $nombreGerente,  $nombreUsuario, $nombreAsociado, $urlFirmaContrato); 
                        $asunto             = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
                        $msg                = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
                        $enviarcopia        = $informacioncorreo->innocoenviarcopia;
                        $enviarpiepagina    = $informacioncorreo->innocoenviarpiepagina;
                        $notificar->correo([$correoGerente], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);
                        $correoGerente = $correoAsociado;
                    }
                }
            }

        	DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function show(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required']);
        $url      = URL::to('/');
        $vehiculo = DB::table('vehiculo as v')
                        ->select('tv.tipvehnombre as tipoVehiculo', 'trv.tirevenombre as tipoReferencia','tmv.timavenombre as tipoMarca',
                                'tcv.ticovenombre as tipoColor','tmvh.timovenombre as tipoModalidad','tcrh.ticavenombre as tipoCarroceria',
                                'tcvh.ticovhnombre as tipoCombustible','a.agennombre as agencia','v.vehiobservacion',
                                'v.tiesveid','v.vehifechaingreso','v.vehinumerointerno','v.vehiplaca','v.vehimodelo','v.vehicilindraje',
                                'v.vehinumeromotor','v.vehinumerochasis','v.vehinumeroserie','v.vehinumeroejes','v.vehirutafoto', 'tev.tiesvenombre as estadoActual',
                                DB::raw("if(v.vehiesmotorregrabado = 1 ,'Sí', 'No') as motorRegrabado"),
                                DB::raw("if(v.vehieschasisregrabado = 1 ,'Sí', 'No') as chasisRegrabado"),
                                DB::raw("if(v.vehiesserieregrabado = 1 ,'Sí', 'No') as serieRegrabado"),
                                DB::raw("CONCAT('$url/archivos/vehiculo/', v.vehiplaca, '/', v.vehirutafoto ) as rutaFotografia"),
                                DB::raw('(SELECT COUNT(vecaesid) AS vecaesid FROM vehiculocambioestado WHERE vehiid = v.vehiid ) AS totalCambioEstadoVehiculo'),
                                DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                    p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                        ->join('asociado as aso', 'aso.asocid', '=', 'v.asocid')
                        ->join('persona as p', 'p.persid', '=', 'aso.persid')
                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                        ->join('tiporeferenciavehiculo as trv', 'trv.tireveid', '=', 'v.tireveid')
                        ->join('tipomarcavehiculo as tmv', 'tmv.timaveid', '=', 'v.timaveid')
                        ->join('tipocolorvehiculo as tcv', 'tcv.ticoveid', '=', 'v.ticoveid')
                        ->join('tipomodalidadvehiculo as tmvh', 'tmvh.timoveid', '=', 'v.timoveid')
                        ->join('tipocarroceriavehiculo as tcrh', 'tcrh.ticaveid', '=', 'v.ticaveid')
                        ->join('tipocombustiblevehiculo as tcvh', 'tcvh.ticovhid', '=', 'v.ticovhid')
                        ->join('tipoestadovehiculo as tev', 'tev.tiesveid', '=', 'v.tiesveid')
                        ->join('agencia as a', 'a.agenid', '=', 'v.agenid')
                        ->where('v.vehiid', $request->vehiculoId)->first(); 

        $cambiosEstadoVehiculo  = [];
        if($vehiculo->totalCambioEstadoVehiculo > 0 ){
            $cambiosEstadoVehiculo =  DB::table('vehiculocambioestado as vce')
                            ->select('vce.vecaesfechahora as fecha','vce.vecaesobservacion as observacion','tev.tiesvenombre as estado',
                                DB::raw("CONCAT(u.usuanombre,' ',u.usuaapellidos) as nombreUsuario"))
                            ->join('tipoestadovehiculo as tev', 'tev.tiesveid', '=', 'vce.tiesveid')
                            ->join('usuario as u', 'u.usuaid', '=', 'vce.vecaesusuaid')
                            ->where('vce.vehiid', $request->vehiculoId)->get();
        }

        return response()->json([ "vehiculo" => $vehiculo, "cambiosEstadoVehiculo" => $cambiosEstadoVehiculo]);
    }

    public function destroy(Request $request)
	{
		$vehiculocontrato = DB::table('vehiculocontrato')->select('tipvehid')->where('tipvehid', $request->codigo)->first();
		if($vehiculocontrato){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo contrato del sistema']);
		}else{
			try {
				$vehiculo = Vehiculo::findOrFail($request->codigo);
				$vehiculo->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}

    public function obtenerConsecutivoContrato($anioActual)
	{
        $consecutivoContrato = DB::table('vehiculocontrato')->select('vehconnumero as consecutivo')
								->where('vehconanio', $anioActual)->orderBy('vehconid', 'desc')->first();

        $consecutivo = ($consecutivoContrato === null) ? 1 : $consecutivoContrato->consecutivo + 1;

        return str_pad($consecutivo,  4, "0", STR_PAD_LEFT);
    }
}