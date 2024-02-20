<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\VehiculoContratoAsocidado;
use App\Models\Vehiculos\VehiculoTarjetaOperacion;
use App\Models\Vehiculos\VehiculoCambioEstado;
use App\Models\Conductor\ConductorVehiculo;
use App\Models\Asociado\AsociadoVehiculo;
use App\Models\Vehiculos\VehiculoPoliza;
use App\Models\Vehiculos\VehiculoSoat;
use App\Models\Vehiculos\VehiculoCrt;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Exception, File, Auth, DB, URL;
use App\Models\Vehiculos\Vehiculo;
use App\Util\redimencionarImagen;
use App\Util\GenerarContrato;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\generales;
use App\Util\notificar;
use Carbon\Carbon;

class AsignarVehiculoController extends Controller
{
    public function index()
    {
        try{
            $data = DB::table('vehiculo as v')->select('v.vehiid',DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
                                                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                                        ->whereIn('v.tiesveid', ['A','S'])
                                                        ->orderBy('v.vehinumerointerno')->get();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function consultarVehiculo(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required']);

        try{
            $url      = URL::to('/');
            $vehiculo = DB::table('vehiculo as v')
                            ->select('tv.tipvehnombre as tipoVehiculo', 'trv.tirevenombre as tipoReferencia','tmv.timavenombre as tipoMarca',
                                    'tcv.ticovenombre as tipoColor','tmvh.timovenombre as tipoModalidad','tcrh.ticavenombre as tipoCarroceria',
                                    'tcvh.ticovhnombre as tipoCombustible','a.agennombre as agencia','v.vehiobservacion','v.tiesveid','v.vehinumeromotor',
                                    'v.vehifechaingreso','v.vehinumerointerno','v.vehiplaca','v.vehimodelo','v.vehicilindraje','v.vehinumerochasis',
                                    'v.vehinumeroserie','v.vehinumeroejes','v.vehirutafoto','tev.tiesvenombre as estadoActual',
                                    DB::raw("if(v.vehiesmotorregrabado = 1 ,'Sí', 'No') as motorRegrabado"),
                                    DB::raw("if(v.vehieschasisregrabado = 1 ,'Sí', 'No') as chasisRegrabado"),
                                    DB::raw("if(v.vehiesserieregrabado = 1 ,'Sí', 'No') as serieRegrabado"),
                                    DB::raw("CONCAT('$url/archivos/vehiculo/', v.vehiplaca, '/', v.vehirutafoto ) as rutaFotografia"))
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

            return response()->json(['success' => true, "vehiculo" => $vehiculo]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function listarContratos(Request $request){
        $this->validate(request(),['vehiculoId' => 'required']);

        try{
            $listaContratos = DB::table('vehiculocontrato as vc')
                                        ->select('vc.vehconid', DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"),'vc.vehconfechainicial','vc.vehconfechafinal', 'p.persid',
                                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"),
                                        DB::raw("(SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 WHERE vcf1.vehconid = vc.vehconid) AS totalFirmas"),
                                        DB::raw("(SELECT COUNT(vcf2.vecofiid) FROM vehiculocontratofirma as vcf2 WHERE vcf2.vehconid = vc.vehconid and vcf2.vecofifirmado = 1) AS totalFirmasRealizadas"))
                                        ->join('asociado as a', 'a.asocid', '=', 'vc.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->where('vc.vehiid', $request->vehiculoId)
                                        ->get();

            return response()->json(['success' => true, "listaContratos" => $listaContratos]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    } 

    public function showPdf(Request $request)
	{
	    $this->validate(request(),['idContrato' => 'required']);  

        try {

            $vehiculoContratoFirma  = DB::table('vehiculocontratofirma as vcf')
                                        ->select('vcf.vehconid',
                                                DB::raw("CONCAT('/archivos/vehiculo/',v.vehiplaca,'/Contrato_',vc.vehconanio,vc.vehconnumero,'.pdf' ) as rutaPdfContrato"),
                                                DB::raw("(SELECT COUNT(vcf1.vecofiid) FROM vehiculocontratofirma as vcf1 WHERE vcf1.vehconid = vcf.vehconid) AS totalFirmas"),
                                                DB::raw("(SELECT COUNT(vcf2.vecofiid) FROM vehiculocontratofirma as vcf2 WHERE vcf2.vehconid = vcf.vehconid and vcf2.vecofifirmado = 1) AS totalFirmasRealizadas"))
                                        ->where('vcf.vehconid', $request->idContrato)
                                        ->join('vehiculocontrato as vc', 'vc.vehconid', '=', 'vcf.vehconid')
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                                        ->first();

            if($vehiculoContratoFirma->totalFirmas === $vehiculoContratoFirma->totalFirmasRealizadas){
                $rutaContrato = public_path().$vehiculoContratoFirma->rutaPdfContrato;
                $data         = file_exists($rutaContrato) ? base64_encode(file_get_contents($rutaContrato)) : 'El archivo no existe';
            }else{
                $data         = GenerarContrato::vehiculo($request->idContrato, 'S');
            }
        
		    return response()->json(['success' => true, "data" => $data]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function reenviarCorreo(Request $request){
        $this->validate(request(),['contradoId' => 'required', 'personaId' => 'required']);

        try {

            $vehiculoContrato = DB::table('vehiculocontrato as vc')
                                ->select('vc.vehconid', 'vcf.vecofiid', DB::raw("CONCAT(vc.vehconanio, vc.vehconnumero) as numeroContrato"), 'p.persid','p.perscorreoelectronico','p.persnumerocelular',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreAsociado"))	
                                ->join('vehiculocontratofirma as vcf', 'vcf.vehconid', '=', 'vc.vehconid')
                                ->join('persona as p', 'p.persid', '=', 'vcf.persid')
                                ->where('vcf.persid', $request->personaId)
                                ->where('vc.vehconid', $request->contradoId)->first();

            $representante         =  DB::table('empresa as e')->select('e.emprcorreo',
                                        DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,'')) as nombreGerente"))
                                        ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                        ->where('emprid', '1')->first();

            $vehconid            = $vehiculoContrato->vehconid;
            $vecofiid            = $vehiculoContrato->vecofiid;
            $numeroContrato      = $vehiculoContrato->numeroContrato;
            $correoEmpresa       = $representante->emprcorreo;
            $nombreGerente       = $representante->nombreGerente;
            $correoAsociado      = $vehiculoContrato->perscorreoelectronico;
            $nombreAsociado      = $vehiculoContrato->nombreAsociado;
            $nombreUsuario       = auth()->user()->usuanombre.' '.auth()->user()->usuaapellidos;           
            $urlFirmaContrato    = asset('firmar/contrato/asociado/'.Crypt::encrypt($vehconid).'/'.Crypt::encrypt($vecofiid));

            $notificar           = new notificar();
            $informacioncorreo   = DB::table('informacionnotificacioncorreo')->where('innoconombre', 'solicitaFirmaContratoAsociado')->first();
            $buscar              = Array('numeroContrato', 'nombreGerente', 'nombreUsuario', 'nombreAsociado', 'urlFirmaContrato');
            $remplazo            = Array($numeroContrato, $nombreGerente,  $nombreUsuario, $nombreAsociado, $urlFirmaContrato); 
            $asunto              = str_replace($buscar,$remplazo,$informacioncorreo->innocoasunto);
            $msg                 = str_replace($buscar,$remplazo,$informacioncorreo->innococontenido);
            $enviarcopia         = $informacioncorreo->innocoenviarcopia;
            $enviarpiepagina     = $informacioncorreo->innocoenviarpiepagina;
            $mensajeNotificacion = $notificar->correo([$correoAsociado], $asunto, $msg, [], $correoEmpresa, $enviarcopia, $enviarpiepagina);            
            $mensajeMostrar      = 'Hemos enviado una notificación al correo electrónico '.$correoAsociado;
            $mensajeMostrar      .= ', donde se detalla cómo completar el proceso de firma del contrato y/o descargar una copia del mismo';

            return response()->json(['success' => true, "message" => $mensajeMostrar]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function listCondutores(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required']);
        try {

            $conductoresVehiculo = DB::table('conductorvehiculo as cv')
                                    ->select('cv.vehiid','cv.convehid', 'p.persid', DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                    ->join('conductor as c', 'c.condid', '=', 'cv.condid')
                                    ->join('persona as p', 'p.persid', '=', 'c.persid')
                                    ->where('cv.vehiid', $request->vehiculoId)->get();

            $conductores = DB::table('persona as p')->select('c.condid', 'p.persid',
                                    DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                    ->join('conductor as c', 'c.persid', '=', 'p.persid')
                                    ->where('c.tiescoid', 'A')
                                    ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                    ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();                            

            return response()->json(['success' => true, "conductoresVehiculo" => $conductoresVehiculo,  "conductores" => $conductores]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function salveConductor(Request $request)
	{
	    $this->validate(request(),['vehiculo' => 'required', 'conductores' => 'required|array|min:1']);

        DB::beginTransaction();
        try {

            foreach($request->conductores as $dataConductor){
                $identificador   = $dataConductor['identificador'];
                $conductorId     = $dataConductor['conductorId'];
                $estadoConductor = $dataConductor['estado']; 

                if($estadoConductor === 'I'){
					$conductorvehiculo = new ConductorVehiculo();
					$conductorvehiculo->condid = $conductorId;
					$conductorvehiculo->vehiid = $request->vehiculo;
					$conductorvehiculo->save();
				}

                if($estadoConductor === 'D'){
					$conductorvehiculo = ConductorVehiculo::findOrFail($identificador);
					$conductorvehiculo->delete();
				}
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
	}
    
    public function listSoat(Request $request)
    {
        $this->validate(request(),['vehiculoId' => 'required']);

        try {
            $generales             = new generales();
            $fechaHoraActual       = Carbon::now();
            $fechaActual           = $fechaHoraActual->format('Y-m-d');
    
            $soatVehiculo = DB::table('vehiculosoat as vs')
                                ->select('vs.vehsoaid','vs.vehiid','vs.vehsoanumero','vs.vehsoafechainicial','vs.vehsoafechafinal','vs.vehsoaextension', 
                                    'vs.vehsoanombrearchivooriginal', 'vs.vehsoanombrearchivoeditado', 'vs.vehsoarutaarchivo',
                                    DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoSoat"),
                                    DB::raw("(SELECT MAX(vehsoafechafinal) FROM vehiculosoat WHERE vehiid = vs.vehiid) AS maxFechaVencimiento"))
                                ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                ->whereRaw('vs.vehsoafechafinal = (SELECT MAX(vehsoafechafinal) FROM vehiculosoat WHERE vehiid = vs.vehiid )')
                                ->where('vs.vehiid', $request->vehiculoId)->first();

            $soatVehiculo        = ($soatVehiculo) ? $soatVehiculo : [];
            $maxFechaVencimiento = ($soatVehiculo) ? $soatVehiculo->maxFechaVencimiento : '';
            $debeCrearRegistro   = ($soatVehiculo) ? $generales->validarFechaVencimiento($fechaActual, $soatVehiculo->maxFechaVencimiento): false;
            $comparadorConsulta  = ($debeCrearRegistro) ? '=' : '<';

            $historialSoatVehiculo = DB::table('vehiculosoat as vs')
                                    ->select('vs.vehsoaid','vs.vehiid','vs.vehsoanumero','vs.vehsoafechainicial','vs.vehsoafechafinal','vs.vehsoaextension', 
                                        'vs.vehsoanombrearchivooriginal', 'vs.vehsoanombrearchivoeditado', 'vs.vehsoarutaarchivo',
                                        DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoSoat"))
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                    ->where('vs.vehiid', $request->vehiculoId)
                                    ->where('vs.vehsoafechafinal', $comparadorConsulta , $maxFechaVencimiento)
                                    ->get();

            return response()->json(['success'      => true,          "debeCrearRegistro"    => $debeCrearRegistro,    "maxFechaVencimiento"  => $maxFechaVencimiento, 
                                    "soatVehiculo"  => $soatVehiculo, "historialSoatVehiculo" => $historialSoatVehiculo]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function salveSoat(Request $request)
	{
        $this->validate(request(),[
            'vehiculoId'       => 'required',
            'codigo'           => 'required',
            'numeroSoat'       => 'required|string|min:4|max:30',
            'fechaInicio' 	   => 'nullable|date|date_format:Y-m-d',
            'fechaVencimiento' => 'nullable|date|date_format:Y-m-d',
            'imagenSoat' 	   => 'nullable|mimes:jpg,png,jpeg,pdf|max:1000'
        ]);

        DB::beginTransaction();
        try {

            //Consulto la placa del vehiculo
            $vehiculo             = DB::table('vehiculo')->select('tiesveid','vehiplaca')->where('vehiid', $request->vehiculoId)->first();
            $fechaHoraActual      = Carbon::now();
            $redimencionarImagen  = new redimencionarImagen();
            $funcion 		      = new generales();
            $documentoPersona     = $request->documento;
            $rutaCarpeta          = public_path().'/archivos/vehiculo/'.$vehiculo->vehiplaca;
            $carpetaServe         = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            $debeActualizarImagen = false;
            if($request->hasFile('imagenSoat')){
                $debeActualizarImagen = true;
                $numeroAleatorio      = rand(100, 1000);
                $file                 = $request->file('imagenSoat');
                $nombreOriginalSoat   = $file->getclientOriginalName();
                $filename             = pathinfo($nombreOriginalSoat, PATHINFO_FILENAME);
                $extension            = pathinfo($nombreOriginalSoat, PATHINFO_EXTENSION);
                $rutaImagenSoat       = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
                $file->move($rutaCarpeta, $rutaImagenSoat);
                $rutaArchivo          = Crypt::encrypt($rutaImagenSoat);
                $extension            = mb_strtoupper($extension,'UTF-8');
                if($extension !== 'PDF')
                    $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaImagenSoat, 480, 340);//Se redimenciona a un solo tipo (ancho * alto)
            }

            $id                               = $request->codigo;
            $vehiculosoat                     = ($id != '000') ? VehiculoSoat::findOrFail($id) : new VehiculoSoat();
            $vehiculosoat->vehiid             = $request->vehiculoId;
            $vehiculosoat->vehsoanumero       = $request->numeroSoat;
            $vehiculosoat->vehsoafechainicial = $request->fechaInicio;
            $vehiculosoat->vehsoafechafinal   = $request->fechaVencimiento;
            if($debeActualizarImagen){
                $vehiculosoat->vehsoaextension             = $extension;
                $vehiculosoat->vehsoanombrearchivooriginal = $nombreOriginalSoat;
                $vehiculosoat->vehsoanombrearchivoeditado  = $rutaImagenSoat;
                $vehiculosoat->vehsoarutaarchivo           = $rutaArchivo;
            }
            $vehiculosoat->save();

            if($id === '000' and $vehiculo->tiesveid === 'S'){
                $estado             = 'A';
                $vehiculo           = Vehiculo::findOrFail($request->vehiculoId);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $request->vehiculoId;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = Auth::id();
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = "La activación del vehículo ha sido realizada tras el registro de un nuevo SOAT";
                $vehiculocambioestado->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
	}

    public function listCrt(Request $request)
    {
        $this->validate(request(),['vehiculoId' => 'required']);

        try {
            $generales             = new generales();
            $fechaHoraActual       = Carbon::now();
            $fechaActual           = $fechaHoraActual->format('Y-m-d');
    
            $crtVehiculo = DB::table('vehiculocrt as vc')
                                ->select('vc.vehcrtid','vc.vehiid','vc.vehcrtnumero','vc.vehcrtfechainicial','vc.vehcrtfechafinal','vc.vehcrtextension', 
                                    'vc.vehcrtnombrearchivooriginal', 'vc.vehcrtnombrearchivoeditado', 'vc.vehcrtrutaarchivo',
                                    DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoCrt"),
                                    DB::raw("(SELECT MAX(vehcrtfechafinal) FROM vehiculocrt WHERE vehiid = vc.vehiid) AS maxFechaVencimiento"))
                                ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                                ->whereRaw('vc.vehcrtfechafinal = (SELECT MAX(vehcrtfechafinal) FROM vehiculocrt WHERE vehiid = vc.vehiid )')
                                ->where('vc.vehiid', $request->vehiculoId)->first();

            $crtVehiculo         = ($crtVehiculo) ? $crtVehiculo : [];
            $maxFechaVencimiento = ($crtVehiculo) ? $crtVehiculo->maxFechaVencimiento : '';
            $debeCrearRegistro   = ($crtVehiculo) ? $generales->validarFechaVencimiento($fechaActual, $crtVehiculo->maxFechaVencimiento): false;
            $comparadorConsulta  = ($debeCrearRegistro) ? '=' : '<';

            $historialCrtVehiculo = DB::table('vehiculocrt as vc')
                                    ->select('vc.vehcrtid','vc.vehiid','vc.vehcrtnumero','vc.vehcrtfechainicial','vc.vehcrtfechafinal','vc.vehcrtextension', 
                                        'vc.vehcrtnombrearchivooriginal', 'vc.vehcrtnombrearchivoeditado', 'vc.vehcrtrutaarchivo',
                                        DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoCrt"))
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vc.vehiid')
                                    ->where('vc.vehcrtfechafinal', $comparadorConsulta, $maxFechaVencimiento)
                                    ->where('vc.vehiid', $request->vehiculoId)->get();

            return response()->json(['success'    => true,         "debeCrearRegistro"   => $debeCrearRegistro,   "maxFechaVencimiento"  => $maxFechaVencimiento, 
                                    "crtVehiculo" => $crtVehiculo, "historialCrtVehiculo" => $historialCrtVehiculo]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function salveCrt(Request $request)
	{
        $this->validate(request(),[
            'vehiculoId'       => 'required',
            'codigo'           => 'required',
            'numeroCrt'        => 'required|string|min:4|max:30',
            'fechaInicio' 	   => 'nullable|date|date_format:Y-m-d',
            'fechaVencimiento' => 'nullable|date|date_format:Y-m-d',
            'imagenCrt' 	   => 'nullable|mimes:jpg,png,jpeg,pdf|max:1000'
        ]);

        DB::beginTransaction();
        try {

            //Consulto la placa del vehiculo
            $vehiculo             = DB::table('vehiculo')->select('tiesveid','vehiplaca')->where('vehiid', $request->vehiculoId)->first();
            $fechaHoraActual      = Carbon::now();
            $redimencionarImagen  = new redimencionarImagen();
            $funcion 		      = new generales();
            $documentoPersona     = $request->documento;
            $rutaCarpeta          = public_path().'/archivos/vehiculo/'.$vehiculo->vehiplaca;
            $carpetaServe         = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            $debeActualizarImagen = false;
            if($request->hasFile('imagenCrt')){
                $debeActualizarImagen = true;
                $numeroAleatorio      = rand(100, 1000);
                $file                 = $request->file('imagenCrt');
                $nombreOriginalCrt    = $file->getclientOriginalName();
                $filename             = pathinfo($nombreOriginalCrt, PATHINFO_FILENAME);
                $extension            = pathinfo($nombreOriginalCrt, PATHINFO_EXTENSION);
                $rutaImagenCrt        = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
                $file->move($rutaCarpeta, $rutaImagenCrt);
                $rutaArchivo          = Crypt::encrypt($rutaImagenCrt);
                $extension            = mb_strtoupper($extension,'UTF-8');
                if($extension !== 'PDF')
                    $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaImagenCrt, 480, 340);//Se redimenciona a un solo tipo (ancho * alto)
            }

            $id                               = $request->codigo;
            $vehiculocrt                     = ($id != '000') ? VehiculoCrt::findOrFail($id) : new VehiculoCrt();
            $vehiculocrt->vehiid             = $request->vehiculoId;
            $vehiculocrt->vehcrtnumero       = $request->numeroCrt;
            $vehiculocrt->vehcrtfechainicial = $request->fechaInicio;
            $vehiculocrt->vehcrtfechafinal   = $request->fechaVencimiento;
            if($debeActualizarImagen){
                $vehiculocrt->vehcrtextension             = $extension;
                $vehiculocrt->vehcrtnombrearchivooriginal = $nombreOriginalCrt;
                $vehiculocrt->vehcrtnombrearchivoeditado  = $rutaImagenCrt;
                $vehiculocrt->vehcrtrutaarchivo           = $rutaArchivo;
            }
            $vehiculocrt->save();

            if($id === '000' and $vehiculo->tiesveid === 'S'){
                $estado             = 'A';
                $vehiculo           = Vehiculo::findOrFail($request->vehiculoId);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $request->vehiculoId;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = Auth::id();
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = "La activación del vehículo ha sido realizada tras el registro de un nuevo CRT";
                $vehiculocambioestado->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
	}

    public function listPoliza(Request $request)
    {
        $this->validate(request(),['vehiculoId' => 'required']);

        try {
            $generales             = new generales();
            $fechaHoraActual       = Carbon::now();
            $fechaActual           = $fechaHoraActual->format('Y-m-d');
    
            $polizaVehiculo = DB::table('vehiculopoliza as vp')
                                ->select('vp.vehpolid','vp.vehiid','vp.vehpolnumeropolizacontractual','vp.vehpolnumeropolizaextcontrac', 'vp.vehpolfechainicial',
                                    'vp.vehpolfechafinal','vp.vehpolextension','vp.vehpolnombrearchivooriginal', 'vp.vehpolnombrearchivoeditado', 'vp.vehpolrutaarchivo',
                                    DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoPoliza"),
                                    DB::raw("(SELECT MAX(vehpolfechafinal) FROM vehiculopoliza WHERE vehiid = vp.vehiid) AS maxFechaVencimiento"))
                                ->join('vehiculo as v', 'v.vehiid', '=', 'vp.vehiid')
                                ->whereRaw('vp.vehpolfechafinal = (SELECT MAX(vehpolfechafinal) FROM vehiculopoliza WHERE vehiid = vp.vehiid )')
                                ->where('vp.vehiid', $request->vehiculoId)->first();

            $polizaVehiculo      = ($polizaVehiculo) ? $polizaVehiculo : [];
            $maxFechaVencimiento = ($polizaVehiculo) ? $polizaVehiculo->maxFechaVencimiento : '';
            $debeCrearRegistro   = ($polizaVehiculo) ? $generales->validarFechaVencimiento($fechaActual, $polizaVehiculo->maxFechaVencimiento): false;
            $comparadorConsulta  = ($debeCrearRegistro) ? '=' : '<';

            $historialPolizasVehiculo = DB::table('vehiculopoliza as vp')
                                        ->select('vp.vehpolid','vp.vehiid','vp.vehpolnumeropolizacontractual','vp.vehpolnumeropolizaextcontrac', 'vp.vehpolfechainicial',
                                            'vp.vehpolfechafinal','vp.vehpolextension', 'vp.vehpolnombrearchivooriginal', 'vp.vehpolnombrearchivoeditado', 'vp.vehpolrutaarchivo',
                                            DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoPoliza"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vp.vehiid')
                                        ->where('vp.vehpolfechafinal', $comparadorConsulta , $maxFechaVencimiento)
                                        ->where('vp.vehiid', $request->vehiculoId)->get();

            return response()->json(['success'       => true,            "debeCrearRegistro"        => $debeCrearRegistro,      "maxFechaVencimiento" => $maxFechaVencimiento, 
                                    "polizaVehiculo" => $polizaVehiculo, "historialPolizasVehiculo" => $historialPolizasVehiculo]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function salvePoliza(Request $request)
	{
        $this->validate(request(),[
            'vehiculoId'                   => 'required',
            'codigo'                       => 'required',
            'numeroPolizaContractual'      => 'required|string|min:4|max:30',
            'numeroPolizaExtraContractual' => 'required|string|min:4|max:30',
            'fechaInicio' 	               => 'nullable|date|date_format:Y-m-d',
            'fechaVencimiento'             => 'nullable|date|date_format:Y-m-d',
            'imagenPoliza' 	               => 'nullable|mimes:jpg,png,jpeg,pdf|max:1000'
        ]);

        DB::beginTransaction();
        try {

            //Consulto la placa del vehiculo
            $vehiculo             = DB::table('vehiculo')->select('tiesveid','vehiplaca')->where('vehiid', $request->vehiculoId)->first();
            $fechaHoraActual      = Carbon::now();
            $redimencionarImagen  = new redimencionarImagen();
            $funcion 		      = new generales();
            $documentoPersona     = $request->documento;
            $rutaCarpeta          = public_path().'/archivos/vehiculo/'.$vehiculo->vehiplaca;
            $carpetaServe         = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            $debeActualizarImagen = false;
            if($request->hasFile('imagenPoliza')){
                $debeActualizarImagen = true;
                $numeroAleatorio      = rand(100, 1000);
                $file                 = $request->file('imagenPoliza');
                $nombreOriginalPoliza = $file->getclientOriginalName();
                $filename             = pathinfo($nombreOriginalPoliza, PATHINFO_FILENAME);
                $extension            = pathinfo($nombreOriginalPoliza, PATHINFO_EXTENSION);
                $rutaImagenPoliza     = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
                $file->move($rutaCarpeta, $rutaImagenPoliza);
                $rutaArchivo          = Crypt::encrypt($rutaImagenPoliza);
                $extension            = mb_strtoupper($extension,'UTF-8');
                if($extension !== 'PDF')
                    $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaImagenPoliza, 480, 340);//Se redimenciona a un solo tipo (ancho * alto)
            }

            $id                                            = $request->codigo;
            $vehiculopoliza                                = ($id != '000') ? VehiculoPoliza::findOrFail($id) : new VehiculoPoliza();
            $vehiculopoliza->vehiid                        = $request->vehiculoId;
            $vehiculopoliza->vehpolnumeropolizacontractual = $request->numeroPolizaContractual;
            $vehiculopoliza->vehpolnumeropolizaextcontrac  = $request->numeroPolizaExtraContractual;
            $vehiculopoliza->vehpolfechainicial            = $request->fechaInicio;
            $vehiculopoliza->vehpolfechafinal              = $request->fechaVencimiento;
            if($debeActualizarImagen){
                $vehiculopoliza->vehpolextension             = $extension;
                $vehiculopoliza->vehpolnombrearchivooriginal = $nombreOriginalPoliza;
                $vehiculopoliza->vehpolnombrearchivoeditado  = $rutaImagenPoliza;
                $vehiculopoliza->vehpolrutaarchivo           = $rutaArchivo;
            }
            $vehiculopoliza->save();

            if($id === '000' and $vehiculo->tiesveid === 'S'){
                $estado             = 'A';
                $vehiculo           = Vehiculo::findOrFail($request->vehiculoId);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $request->vehiculoId;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = Auth::id();
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = "La activación del vehículo ha sido realizada tras el registro de una nueva póliza";
                $vehiculocambioestado->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
	}

    public function listTarjetaOperacion(Request $request)
    {
        $this->validate(request(),['vehiculoId' => 'required']);

        try {
            $generales             = new generales();
            $fechaHoraActual       = Carbon::now();
            $fechaActual           = $fechaHoraActual->format('Y-m-d');
    
            $tarjetaOperacionVehiculo = DB::table('vehiculotarjetaoperacion as vto')
                                        ->select('vto.vetaopaid','vto.vehiid','vto.tiseveid','vto.vetaopnumero', 'vto.vetaopfechainicial','vto.vetaopfechafinal','vto.vetaopradioaccion',
                                            'vto.vetaopenteadministrativo','vto.vetaopextension','vto.vetaopnombrearchivooriginal', 'vto.vetaopnombrearchivoeditado', 'vto.vetaoprutaarchivo',                                        
                                            DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoTarjetaOperacion"),
                                            DB::raw("(SELECT MAX(vetaopfechafinal) FROM vehiculotarjetaoperacion WHERE vehiid = vto.vehiid) AS maxFechaVencimiento"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vto.vehiid')
                                        ->whereRaw('vto.vetaopfechafinal = (SELECT MAX(vetaopfechafinal) FROM vehiculotarjetaoperacion WHERE vehiid = vto.vehiid )')
                                        ->where('vto.vehiid', $request->vehiculoId)->first();

            $tarjetaOperacionVehiculo = ($tarjetaOperacionVehiculo) ? $tarjetaOperacionVehiculo : [];
            $maxFechaVencimiento      = ($tarjetaOperacionVehiculo) ? $tarjetaOperacionVehiculo->maxFechaVencimiento : '';
            $debeCrearRegistro        = ($tarjetaOperacionVehiculo) ? $generales->validarFechaVencimiento($fechaActual, $tarjetaOperacionVehiculo->maxFechaVencimiento): false;
            $comparadorConsulta       = ($debeCrearRegistro) ? '=' : '<';

            $tipoServiciosVehiculos   = DB::table('tiposerviciovehiculo')->select('tiseveid','tisevenombre')->orderBy('tisevenombre')->get();

            $historialTarjetaOperacion = DB::table('vehiculotarjetaoperacion as vto')
                                        ->select('vto.vetaopaid','vto.vehiid','tsv.tisevenombre','vto.vetaopnumero', 'vto.vetaopfechainicial','vto.vetaopfechafinal','vto.vetaopradioaccion',
                                        'vto.vetaopenteadministrativo','vto.vetaopextension', 'vto.vetaopnombrearchivooriginal', 'vto.vetaopnombrearchivoeditado', 'vto.vetaoprutaarchivo',
                                        DB::raw("if(vto.vetaopradioaccion = 'M' ,'Municipal', 'Nacional') as radioAccion"),
                                        DB::raw("if(vto.vetaopenteadministrativo = 'M' ,'Ministerio', 'Tránsito') as enteAdministrativo"),
                                        DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoTarjetaOperacion"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'vto.vehiid')
                                        ->join('tiposerviciovehiculo as tsv', 'tsv.tiseveid', '=', 'vto.tiseveid')
                                        ->where('vto.vetaopfechafinal', $comparadorConsulta , $maxFechaVencimiento)
                                        ->where('vto.vehiid', $request->vehiculoId)->get();

            return response()->json(['success'                 => true,                     "debeCrearRegistro"          => $debeCrearRegistro,          "maxFechaVencimiento"   => $maxFechaVencimiento, 
                                    "tarjetaOperacionVehiculo" => $tarjetaOperacionVehiculo, "historialTarjetaOperacion" => $historialTarjetaOperacion, "tipoServiciosVehiculos" => $tipoServiciosVehiculos]);
        } catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function salveTarjetaOperacion(Request $request)
	{
        $this->validate(request(),[
            'vehiculoId'             => 'required',
            'codigo'                 => 'required',
            'tipoServicio'           => 'required|string|min:1|max:2',
            'numeroTarjetaOperacion' => 'required|string|min:4|max:30',
            'fechaInicio' 	         => 'nullable|date|date_format:Y-m-d',
            'fechaVencimiento'       => 'nullable|date|date_format:Y-m-d',
            'enteAdministrativo'     => 'required|string|min:1|max:2',
            'radioAccion'            => 'required|string|min:1|max:2',
            'imagenTarjetaOperacion' => 'nullable|mimes:jpg,png,jpeg,pdf|max:1000'
        ]);

        DB::beginTransaction();
        try {

            //Consulto la placa del vehiculo
            $vehiculo             = DB::table('tiesveid','vehiculo')->select('vehiplaca')->where('vehiid', $request->vehiculoId)->first();
            $fechaHoraActual      = Carbon::now();
            $redimencionarImagen  = new redimencionarImagen();
            $funcion 		      = new generales();
            $documentoPersona     = $request->documento;
            $rutaCarpeta          = public_path().'/archivos/vehiculo/'.$vehiculo->vehiplaca;
            $carpetaServe         = (is_dir($rutaCarpeta)) ? $rutaCarpeta : File::makeDirectory($rutaCarpeta, $mode = 0775, true, true); 
            $debeActualizarImagen = false;
            if($request->hasFile('imagenTarjetaOperacion')){
                $debeActualizarImagen       = true;
                $numeroAleatorio            = rand(100, 1000);
                $file                       = $request->file('imagenTarjetaOperacion');
                $nombreOriginalTarjetaOpe   = $file->getclientOriginalName();
                $filename                   = pathinfo($nombreOriginalTarjetaOpe, PATHINFO_FILENAME);
                $extension                  = pathinfo($nombreOriginalTarjetaOpe, PATHINFO_EXTENSION);
                $rutaImagenTarjetaOperacion = $numeroAleatorio."_".$funcion->quitarCaracteres($filename).'.'.$extension;
                $file->move($rutaCarpeta, $rutaImagenTarjetaOperacion);
                $rutaArchivo                = Crypt::encrypt($rutaImagenTarjetaOperacion);
                $extension                  = mb_strtoupper($extension,'UTF-8');
                if($extension !== 'PDF')
                    $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$rutaImagenTarjetaOperacion, 480, 340);//Se redimenciona a un solo tipo (ancho * alto)
            }

            $id                                                 = $request->codigo;
            $vehiculotarjetaoperacion                           = ($id != '000') ? VehiculoTarjetaOperacion::findOrFail($id) : new VehiculoTarjetaOperacion();
            $vehiculotarjetaoperacion->vehiid                   = $request->vehiculoId;
            $vehiculotarjetaoperacion->tiseveid                 = $request->tipoServicio;
            $vehiculotarjetaoperacion->vetaopnumero             = $request->numeroTarjetaOperacion;
            $vehiculotarjetaoperacion->vetaopfechainicial       = $request->fechaInicio;
            $vehiculotarjetaoperacion->vetaopfechafinal         = $request->fechaVencimiento;
            $vehiculotarjetaoperacion->vetaopenteadministrativo = $request->enteAdministrativo;
            $vehiculotarjetaoperacion->vetaopradioaccion        = $request->radioAccion;
            if($debeActualizarImagen){
                $vehiculotarjetaoperacion->vetaopextension             = $extension;
                $vehiculotarjetaoperacion->vetaopnombrearchivooriginal = $nombreOriginalTarjetaOpe;
                $vehiculotarjetaoperacion->vetaopnombrearchivoeditado  = $rutaImagenTarjetaOperacion;
                $vehiculotarjetaoperacion->vetaoprutaarchivo           = $rutaArchivo;
            }
            $vehiculotarjetaoperacion->save();

            if($id === '000' and $vehiculo->tiesveid === 'S'){
                $estado             = 'A';
                $vehiculo           = Vehiculo::findOrFail($request->vehiculoId);
                $vehiculo->tiesveid = $estado;
                $vehiculo->save();

                $vehiculocambioestado 					 = new VehiculoCambioEstado();
                $vehiculocambioestado->vehiid            = $request->vehiculoId;
                $vehiculocambioestado->tiesveid          = $estado;
                $vehiculocambioestado->vecaesusuaid      = Auth::id();
                $vehiculocambioestado->vecaesfechahora   = $fechaHoraActual;
                $vehiculocambioestado->vecaesobservacion = "La activación del vehículo ha sido realizada tras el registro de una nueva tarjeta de operación";
                $vehiculocambioestado->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
	}
}