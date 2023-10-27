<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Conductor\ConductorVehiculo;
use App\Models\Asociado\AsociadoVehiculo;
use App\Models\Vehiculos\VehiculoPoliza;
use App\Models\Vehiculos\VehiculoSoat;
use App\Models\Vehiculos\VehiculoCrt;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Util\redimencionarImagen;
use Illuminate\Http\Request;
use Exception, File, DB;
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

    public function asignacionVehiculo(Request $request)
	{
        $this->validate(request(),['vehiculoId' => 'required']);

        try {
            $asociadoVehiculos  = DB::table('asociadovehiculo as av')
                                    ->select('av.asovehid','p.persid','a.asocid', DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                    ->join('asociado as a', 'a.asocid', '=', 'av.asocid')
                                    ->join('persona as p', 'p.persid', '=', 'a.persid')
                                    ->where('av.vehiid', $request->vehiculoId)->get();

            $conductoresVehiculo = DB::table('conductorvehiculo as cv')
                                    ->select('cv.vehiid','cv.convehid', 'p.persid', DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                    ->join('conductor as c', 'c.condid', '=', 'cv.condid')
                                    ->join('persona as p', 'p.persid', '=', 'c.persid')
                                    ->where('cv.vehiid', $request->vehiculoId)->get();

            $soatVehiculo = DB::table('vehiculosoat as vs')
                                    ->select('vs.vehsoaid','vs.vehiid','vs.vehsoanumero','vs.vehsoafechainicial','vs.vehsoafechafinal','vs.vehsoaextension', 
                                        'vs.vehsoanombrearchivooriginal', 'vs.vehsoanombrearchivoeditado', 'vs.vehsoarutaarchivo',
                                         DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoSoat"))
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                                    ->where('vs.vehiid', $request->vehiculoId)->get();


            $crtVehiculo = [];         

            $polizasVehiculo = DB::table('vehiculopoliza as vp')
                                    ->select('vp.vehpolid','vp.vehiid','vp.vehpolnumeropolizacontractual','vp.vehpolnumeropolizaextcontrac','vp.vehpolfechainicial',
                                    'vp.vehpolfechafinal', 'vp.vehpolextension', 'vp.vehpolnombrearchivooriginal', 'vp.vehpolnombrearchivoeditado', 'vp.vehpolrutaarchivo',
                                    DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoPoliza"))
                                    ->join('vehiculo as v', 'v.vehiid', '=', 'vp.vehiid')
                                    ->where('vp.vehiid', $request->vehiculoId)->get();

            return response()->json(['success' => true, "asociadoVehiculos" => $asociadoVehiculos,  "conductoresVehiculo" => $conductoresVehiculo, "soatVehiculo" => $soatVehiculo,
                                                        "crtVehiculo"       => $crtVehiculo,        "polizasVehiculo"    => $polizasVehiculo]);
        }catch (Exception $error){
            return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
        }
    }

    public function listAsociados()
    {
        $asociados = DB::table('persona as p')->select('a.asocid', 'p.persid',
                                DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                ->where('a.tiesasid', 'A')
                                ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();
                         
        return response()->json(["asociados" => $asociados]);
    }

    public function salveAsocido(Request $request)
	{
	    $this->validate(request(),['vehiculo' => 'required', 'asociados' => 'required|array|min:1']);

        DB::beginTransaction();
        try {

            foreach($request->asociados as $dataAsociado){
                $identificador  = $dataAsociado['identificador'];
                $asociadoId     = $dataAsociado['asociadoId'];
                $estadoAsociado = $dataAsociado['estado']; 

                if($estadoAsociado === 'I'){
					$asociadovehiculo = new AsociadoVehiculo();
					$asociadovehiculo->asocid = $asociadoId;
					$asociadovehiculo->vehiid = $request->vehiculo;
					$asociadovehiculo->save();
				}

                if($estadoAsociado === 'D'){
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

    public function listCondutores()
    {
        $conductores = DB::table('persona as p')->select('c.condid', 'p.persid',
                                DB::raw("CONCAT(p.persdocumento,' ',p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                ->join('conductor as c', 'c.persid', '=', 'p.persid')
                                ->where('c.tiescoid', 'A')
                                ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();
                         
        return response()->json(["conductores" => $conductores]);
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

        $fechaHoraActual       = Carbon::now();
        $fechaActual           = $fechaHoraActual->format('Y-m-d');
        $fechaAnterior         = $fechaHoraActual->subDays(10);


        /*  DB::raw("(SELECT COUNT(cl.conlicid) AS conlicid FROM conductorlicencia as cl
                                                        INNER JOIN conductor as c on c.condid = cl.condid
                                                        WHERE c.persid = p.persid 
                                                        and conlicfechavencimiento > '$fechaAnterior'
                                                        and conlicfechavencimiento < '$fechaActual'
                                                        and conlicfechavencimiento = (SELECT MAX(conlicfechavencimiento) FROM conductorlicencia WHERE condid = c.condid )
                                                        ) AS totalLicenciaPorVencer")*/


        /*DB::raw("(SELECT COUNT(vehsoaid) AS vehsoaid  FROM vehiculosoat WHERE vehiid = vs.vehiid
        and vehsoafechafinal > '$fechaAnterior'
        and vehsoafechafinal < '$fechaActual'
        and vehsoafechafinal = (SELECT MAX(vehsoafechafinal) FROM vehiculosoat WHERE vehiid = vs.vehiid)
        ) AS totalSoatPorVencer")*/


        //vehsoafechafinal = epr.encpreid AND
        $soatVehiculo = DB::table('vehiculosoat as vs')
                            ->select('vs.vehsoaid','vs.vehiid','vs.vehsoanumero','vs.vehsoafechainicial','vs.vehsoafechafinal','vs.vehsoaextension', 
                                'vs.vehsoanombrearchivooriginal', 'vs.vehsoanombrearchivoeditado', 'vs.vehsoarutaarchivo',
                                DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoSoat"),
                                DB::raw("(SELECT MAX(vehsoafechafinal) FROM vehiculosoat WHERE vehiid = vs.vehiid) AS maxFechaFinal"),
                                DB::raw("(SELECT COUNT(vehsoaid) AS vehsoaid  FROM vehiculosoat WHERE vehiid = vs.vehiid
                                        and vehsoafechafinal > '$fechaAnterior'
                                        and vehsoafechafinal < '$fechaActual'
                                        and vehsoafechafinal = (SELECT MAX(vehsoafechafinal) FROM vehiculosoat WHERE vehiid = vs.vehiid)
                                        ) AS totalSoatPorVencer"))
                            ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                            ->whereRaw('vs.vehsoafechafinal = (SELECT MAX(vehsoafechafinal) FROM vehiculosoat WHERE vehiid = vs.vehiid )')
                            ->where('vs.vehiid', $request->vehiculoId)->first();

        $maxFechaFinal = ($soatVehiculo) ? $soatVehiculo->maxFechaFinal : '';

        $historialSoatVehiculo = DB::table('vehiculosoat as vs')
                            ->select('vs.vehsoaid','vs.vehiid','vs.vehsoanumero','vs.vehsoafechainicial','vs.vehsoafechafinal','vs.vehsoaextension', 
                                'vs.vehsoanombrearchivooriginal', 'vs.vehsoanombrearchivoeditado', 'vs.vehsoarutaarchivo',
                                 DB::raw("CONCAT('archivos/vehiculo/', v.vehiplaca) as rutaAdjuntoSoat"))
                            ->join('vehiculo as v', 'v.vehiid', '=', 'vs.vehiid')
                            ->where('vs.vehsoafechafinal', '<', $maxFechaFinal)
                            ->where('vs.vehiid', $request->vehiculoId)->get();

        return response()->json(["soatVehiculo" => $soatVehiculo, "historialSoatVehiculo" => $historialSoatVehiculo]);
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
                $file                 = $request->file('imagenSoat');
                $nombreOriginalSoat   = $file->getclientOriginalName();
                $filename             = pathinfo($nombreOriginalSoat, PATHINFO_FILENAME);
                $extension            = pathinfo($nombreOriginalSoat, PATHINFO_EXTENSION);
                $rutaImagenSoat       = $request->vehiculoId."_".$funcion->quitarCaracteres($filename).'.'.$extension;
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
}