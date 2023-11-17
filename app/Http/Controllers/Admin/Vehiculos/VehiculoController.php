<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\VehiculoContrato;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Exception, Auth, File, DB, URL;
use App\Models\Vehiculos\Vehiculo;
use App\Util\redimencionarImagen;
use Illuminate\Http\Request;
use App\Util\generales;
use Carbon\Carbon;

class VehiculoController extends Controller
{
    public function index()
    {
        $data = DB::table('vehiculo as v')->select('v.vehiid','v.vehifechaingreso','v.vehinumerointerno','v.vehiplaca','v.vehimodelo','v.vehicilindraje',
                                                    'v.vehinumeromotor','v.vehinumerochasis','v.vehinumeroserie','v.vehinumeroejes','tv.tipvehnombre')
                                                    ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
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

        $consultaTipoCarroceria      = DB::table('tipocarroceriavehiculo')->select('ticaveid','ticavenombre');
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

        $tipocombustiblevehiculos = DB::table('tipocombustiblevehiculo')->select('ticovhid','ticovhnombre')->orderBy('ticovhnombre')->get();
        $tipomodalidadvehiculos   = DB::table('tipomodalidadvehiculo')->select('timoveid','timovenombre')->orderBy('timovenombre')->get();
		
		$vehiculo        = [];
		if($request->tipo === 'U'){
            $url      = URL::to('/');
			$vehiculo = DB::table('vehiculo')
                                    ->select('vehiid','tipvehid','tireveid','timaveid','ticoveid','timoveid','ticaveid','ticovhid','agenid',
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
                                'vehiculo' => $vehiculo]);
	}

    public function salve(Request $request)
	{
        $id       = $request->codigo;
        $vehiculo = ($id != 000) ? Vehiculo::findOrFail($id) : new Vehiculo();

	    $this->validate(request(),[
                'tipoVehiculo'          => 'required|numeric',
                'tipoReferencia'        => 'required|numeric',
                'tipoMarca'             => 'required|numeric',
                'tipoCombustible'       => 'required|numeric',
                'tipoModalidad'         => 'required|numeric',
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
                $fechaHoraActual              = Carbon::now();
                $anioActual                   = $fechaHoraActual->year;
                $fechaInicialContrato         = Carbon::parse($request->fechaInicialContrato);
                $fechaInicialContratoAdiciona = $fechaInicialContrato->addYear();
                $fechaFinalContrato           = $fechaInicialContratoAdiciona->toDateString();
				$empresa                      = DB::table('empresa')->select('persidrepresentantelegal')->where('emprid', '1')->first();
                $vehiculoMaxConsecutio        = Vehiculo::latest('vehiid')->first();
                $vehiid                       = $vehiculoMaxConsecutio->vehiid;

                $vehiculocontrato                     = new VehiculoContrato();
                $vehiculocontrato->vehiid             = $vehiid;
                $vehiculocontrato->persidgerente      = $empresa->persidrepresentantelegal;;
                $vehiculocontrato->vehconanio         = $anioActual;
                $vehiculocontrato->vehconnumero       = $this->obtenerConsecutivoContrato($anioActual);
                $vehiculocontrato->vehconfechainicial = $request->fechaInicialContrato;
                $vehiculocontrato->vehconfechafinal   = $fechaFinalContrato;
                $vehiculocontrato->vehconobservacion  = 'Se ha generado el contrato del vehículo por primera vez. Este procedimiento fue llevado a cabo por '.auth()->user()->usuanombre.' en la fecha '.$fechaHoraActual;
                $vehiculocontrato->save();
            }

        	DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$vehiculo = DB::table('vehiculo')->select('tipvehid')->where('tipvehid', $request->codigo)->first();
		if($vehiculo){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo del sistema']);
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