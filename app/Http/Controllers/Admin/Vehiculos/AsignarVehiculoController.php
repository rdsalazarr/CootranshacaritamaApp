<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Vehiculos\VehiculoContratoAsocidado;
use App\Models\Vehiculos\VehiculoTarjetaOperacion;
use App\Models\Conductor\ConductorVehiculo;
use App\Models\Asociado\AsociadoVehiculo;
use App\Models\Vehiculos\VehiculoPoliza;
use App\Models\Vehiculos\VehiculoSoat;
use App\Models\Vehiculos\VehiculoCrt;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Util\redimencionarImagen;
use Exception, File, DB, URL;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\generales;
use Carbon\Carbon;

class AsignarVehiculoController extends Controller
{
    public function index()
    {
        $data = DB::table('vehiculo as v')->select('v.vehiid',DB::raw("CONCAT(tv.tipvehnombre,' ',v.vehiplaca,' ',v.vehinumerointerno) as nombreVehiculo"))
                                                    ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                                    ->where('v.tiesveid', 'A')
                                                    ->orderBy('v.vehinumerointerno')->get();
        return response()->json(["data" => $data]);
    }

    public function consultarVehiculo(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required']);
        $url      = URL::to('/');
        $vehiculo = DB::table('vehiculo as v')
                        ->select('tv.tipvehnombre as tipoVehiculo', 'trv.tirevenombre as tipoReferencia','tmv.timavenombre as tipoMarca',
                                'tcv.ticovenombre as tipoColor','tmvh.timovenombre as tipoModalidad','tcrh.ticavenombre as tipoCarroceria',
                                'tcvh.ticovhnombre as tipoCombustible','a.agennombre as agencia','v.vehiobservacion',
                                'v.tiesveid','v.vehifechaingreso','v.vehinumerointerno','v.vehiplaca','v.vehimodelo','v.vehicilindraje',
                                'v.vehinumeromotor','v.vehinumerochasis','v.vehinumeroserie','v.vehinumeroejes','v.vehirutafoto',
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
                        ->join('agencia as a', 'a.agenid', '=', 'v.agenid')
                        ->where('v.vehiid', $request->vehiculoId)->first(); 

        return response()->json([ "vehiculo" => $vehiculo]);
    }

    public function listAsociados(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required']);

        $asociadoVehiculos  = DB::table('asociadovehiculo as av')
                                    ->select('av.asovehid','p.persid','a.asocid', DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                    ->join('asociado as a', 'a.asocid', '=', 'av.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->where('av.vehiid', $request->vehiculoId)->get();

        $asociados = DB::table('persona as p')->select('a.asocid', 'p.persid',
                                DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                ->where('a.tiesasid', 'A')
                                ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();
                         
        return response()->json(["asociadoVehiculos" => $asociadoVehiculos, "asociados" => $asociados]);
    }

    public function salveAsocido(Request $request)
	{
	    $this->validate(request(),['vehiculo' => 'required', 'asociados' => 'required|array|min:1']);

        DB::beginTransaction();
        try {

            $vehiculoId       = $request->vehiculo;
            $vehiculocontrato = DB::table('vehiculocontrato')
                                    ->select('vehconid', 'vehconfechafinal')
                                    ->where('vehiid', $vehiculoId)
                                    ->where('vehconfechafinal', function ($query) use ($vehiculoId) {
                                        $query->selectRaw('MAX(vehconfechafinal)')
                                            ->from('vehiculocontrato')
                                            ->where('vehiid', $vehiculoId);
                                    })
                                    ->first();

            $vehconid = $vehiculocontrato->vehconid;

            foreach($request->asociados as $dataAsociado){
                $identificador  = $dataAsociado['identificador'];
                $asociadoId     = $dataAsociado['asociadoId'];
                $estadoAsociado = $dataAsociado['estado']; 

                if($estadoAsociado === 'I'){
					$asociadovehiculo         = new AsociadoVehiculo();
					$asociadovehiculo->asocid = $asociadoId;
					$asociadovehiculo->vehiid = $vehiculoId;
					$asociadovehiculo->save();

                    $vehiculocontratoasociado           = new VehiculoContratoAsocidado();
                    $vehiculocontratoasociado->asocid   = $asociadoId;
                    $vehiculocontratoasociado->vehconid = $vehconid;
					$vehiculocontratoasociado->save();
				}

                if($estadoAsociado === 'D'){

                    $vehiculocontratoasociado = DB::table('vehiculocontratoasociado')
                                                    ->select('vecoasid')
                                                    ->where('vehconid', $vehconid)
                                                    ->where('asocid', $asociadoId)
                                                    ->first();

                    $vehiculocontratoasociado = VehiculoContratoAsocidado::findOrFail($vehiculocontratoasociado->vecoasid);
                    $vehiculocontratoasociado->delete();

					$asociadovehiculo = AsociadoVehiculo::findOrFail($identificador);
					$asociadovehiculo->delete();
				}
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
	}

    public function showPdf(Request $request)
	{
	    $this->validate(request(),['vehiculoId' => 'required', 'idPersona' => 'required']);

        try {
            $vehiculoId       = $request->vehiculoId;
            $vehiculocontrato = DB::table('vehiculocontrato')
                                ->select('vehconid', 'vehconfechainicial', DB::raw("CONCAT(vehconanio, vehconnumero) as numeroContrato"))
                                ->where('vehiid', $vehiculoId)
                                ->where('vehconfechafinal', function ($query) use ($vehiculoId) {
                                    $query->selectRaw('MAX(vehconfechafinal)')
                                        ->from('vehiculocontrato')
                                        ->where('vehiid', $vehiculoId);
                                })
                                ->first();

            $dataRadicado   = DB::table('informaciongeneralpdf')->select('ingpdftitulo','ingpdfcontenido')->where('ingpdfnombre', 'contratoVehiculo')->first();       
            $numeroContrato = $vehiculocontrato->numeroContrato;
            $fechaContrato  = $vehiculocontrato->vehconfechainicial;

            $asociadoVehiculo  = DB::table('asociadovehiculo as av')
                                        ->select('av.asovehid','p.persid','a.asocid', 'p.persdocumento', DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                            'ti.tipidesigla','me.muninombre as nombreMunicipioExpedicion','p.persdireccion','p.persnumerocelular',
                                            'v.vehinumerointerno','v.vehiplaca','v.vehimodelo','v.vehimodelo','v.vehicilindraje','v.vehiobservacion','tmv.timavenombre',
                                            'tcv.ticovenombre','tcrh.ticavenombre','tv.tipvecapacidad',
                                            DB::raw("CONCAT(tv.tipvehnombre,if(tv.tipvehreferencia is null ,'', tv.tipvehreferencia)) as referenciaVehiculo"))
                                        ->join('vehiculo as v', 'v.vehiid', '=', 'av.vehiid')
                                        ->join('tipovehiculo as tv', 'tv.tipvehid', '=', 'v.tipvehid')
                                        ->join('tipomarcavehiculo as tmv', 'tmv.timaveid', '=', 'v.timaveid')
                                        ->join('tipocolorvehiculo as tcv', 'tcv.ticoveid', '=', 'v.ticoveid')
                                        ->join('tipocarroceriavehiculo as tcrh', 'tcrh.ticaveid', '=', 'v.ticaveid')
                                        ->join('asociado as a', 'a.asocid', '=', 'av.asocid')
                                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                                        ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                        ->join('municipio as me', function($join)
                                        {
                                            $join->on('me.munidepaid', '=', 'p.persdepaidexpedicion');
                                            $join->on('me.muniid', '=', 'p.persmuniidexpedicion'); 
                                        })
                                        ->where('av.vehiid', $request->vehiculoId)
                                        ->where('p.persid', $request->idPersona)->first();

            $empresa    = DB::table('empresa as e')->select('p.persdocumento',  DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                             p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"),
                                             'me.muninombre as nombreMunicipioExpedicion','ti.tipidesigla')
                                            ->join('persona as p', 'p.persid', '=', 'e.persidrepresentantelegal')
                                            ->join('tipoidentificacion as ti', 'ti.tipideid', '=', 'p.tipideid')
                                            ->join('municipio as me', function($join)
                                            {
                                                $join->on('me.munidepaid', '=', 'p.persdepaidexpedicion');
                                                $join->on('me.muniid', '=', 'p.persmuniidexpedicion'); 
                                            })
                                            ->where('e.emprid', 1)->first();

            $nombreGerente              = $empresa->nombrePersona;
            $tpDocumentoGerente         = $empresa->tipidesigla;
            $documentoGerente           = number_format($empresa->persdocumento, 0, ',', '.');
            $ciudadExpDocumentoGerente  = $empresa->nombreMunicipioExpedicion;;

            $nombreAsociado             = $asociadoVehiculo->nombrePersona;
            $tpDocumentoAsociado        = $asociadoVehiculo->tipidesigla;
            $documentoAsociado          = number_format($asociadoVehiculo->persdocumento, 0, ',', '.');
            $ciudadExpDocumentoAsociado = $asociadoVehiculo->nombreMunicipioExpedicion;
            $direccionAsociado          = $asociadoVehiculo->persdireccion;
            $telefonoAsociado           = $asociadoVehiculo->persnumerocelular;
            $placaVehiculo              = $asociadoVehiculo->vehiplaca;
            $numeroInternoVehiculo      = $asociadoVehiculo->vehinumerointerno;

            $claseVehiculo              = $asociadoVehiculo->referenciaVehiculo;
            $cilindrajeVehiculo         = $asociadoVehiculo->vehicilindraje;
            $carroceriaVehiculo         = $asociadoVehiculo->ticavenombre;
            $modeloVehiculo             = $asociadoVehiculo->vehimodelo;
            $marcaVehiculo              = $asociadoVehiculo->timavenombre;
            $colorVehiculo              = $asociadoVehiculo->ticovenombre;

            $capacidadVehiculo          = str_pad($asociadoVehiculo->tipvecapacidad, 2, "0", STR_PAD_LEFT) ;
            $documentosAdionales        = '1 Fotografía, Fotocopia Cédula, Fotocopia Tarjeta de Propiedad a su nombre y Reseña del DAS.';
            $observacionGeneral         = $asociadoVehiculo->vehiobservacion;

            $buscar                     = Array('numeroContrato', 'fechaContrato', 'nombreGerente', 'tpDocumentoGerente','documentoGerente','ciudadExpDocumentoGerente',
                                                'nombreAsociado','tpDocumentoAsociado', 'documentoAsociado', 'ciudadExpDocumentoAsociado', 'direccionAsociado',
                                                'telefonoAsociado', 'placaVehiculo', 'numeroInternoVehiculo', 'claseVehiculo', 'cilindrajeVehiculo', 'carroceriaVehiculo',
                                                'modeloVehiculo', 'marcaVehiculo', 'colorVehiculo', 'capacidadVehiculo', 'documentosAdionales', 'observacionGeneral'
                                            );
            $remplazo                   = Array($numeroContrato, $fechaContrato, $nombreGerente, $tpDocumentoGerente, $documentoGerente, $ciudadExpDocumentoGerente,
                                                $nombreAsociado, $tpDocumentoAsociado, $documentoAsociado, $ciudadExpDocumentoAsociado, $direccionAsociado,
                                                $telefonoAsociado, $placaVehiculo, $numeroInternoVehiculo, $claseVehiculo, $cilindrajeVehiculo, $carroceriaVehiculo,
                                                $modeloVehiculo, $marcaVehiculo, $colorVehiculo, $capacidadVehiculo, $documentosAdionales, $observacionGeneral
                                            ); 
            $titulo                      = str_replace($buscar,$remplazo,$dataRadicado->ingpdftitulo);
            $contenido                   = str_replace($buscar,$remplazo,$dataRadicado->ingpdfcontenido); 
            $generarPdf                  = new generarPdf();
            $data                        = $generarPdf->contratoVehiculo($titulo, $contenido, $numeroContrato, $placaVehiculo, 'S' );
        
		    return response()->json(["data" => $data]);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
    }

    public function listCondutores(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required']);

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
                         
        return response()->json(["conductoresVehiculo" => $conductoresVehiculo,  "conductores" => $conductores]);
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

        return response()->json(["debeCrearRegistro" => $debeCrearRegistro, "maxFechaVencimiento"  => $maxFechaVencimiento, 
                                 "soatVehiculo"      => $soatVehiculo,      "historialSoatVehiculo" => $historialSoatVehiculo]);
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
            $vehiculo      = DB::table('vehiculo')->select('vehiplaca')->where('vehiid', $request->vehiculoId)->first();

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
            $vehiculosoat                     = ($id != 000) ? VehiculoSoat::findOrFail($id) : new VehiculoSoat();
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

        return response()->json(["debeCrearRegistro" => $debeCrearRegistro, "maxFechaVencimiento"  => $maxFechaVencimiento, 
                                 "crtVehiculo"       => $crtVehiculo,       "historialCrtVehiculo" => $historialCrtVehiculo]);
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
            $vehiculo      = DB::table('vehiculo')->select('vehiplaca')->where('vehiid', $request->vehiculoId)->first();

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
            $vehiculocrt                     = ($id != 000) ? VehiculoCrt::findOrFail($id) : new VehiculoCrt();
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

        return response()->json(["debeCrearRegistro" => $debeCrearRegistro, "maxFechaVencimiento"      => $maxFechaVencimiento, 
                                 "polizaVehiculo"    => $polizaVehiculo,    "historialPolizasVehiculo" => $historialPolizasVehiculo]);
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
            $vehiculo      = DB::table('vehiculo')->select('vehiplaca')->where('vehiid', $request->vehiculoId)->first();

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
            $vehiculopoliza                                = ($id != 000) ? VehiculoPoliza::findOrFail($id) : new VehiculoPoliza();
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

        return response()->json(["debeCrearRegistro"        => $debeCrearRegistro,       "maxFechaVencimiento"        => $maxFechaVencimiento, 
                                 "tarjetaOperacionVehiculo" => $tarjetaOperacionVehiculo, "historialTarjetaOperacion" => $historialTarjetaOperacion,
                                 "tipoServiciosVehiculos"   => $tipoServiciosVehiculos]);
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
            $vehiculo             = DB::table('vehiculo')->select('vehiplaca')->where('vehiid', $request->vehiculoId)->first();
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
            $vehiculotarjetaoperacion                           = ($id != 000) ? VehiculoTarjetaOperacion::findOrFail($id) : new VehiculoTarjetaOperacion();
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

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
        } catch (Exception $error){
            DB::rollback();
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
	}
}