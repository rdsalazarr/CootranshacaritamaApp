<?php

namespace App\Http\Controllers\Admin\Empresa;

use App\Models\Empresa\ConfiguracionEncomienda;
use App\Models\Empresa\MensajeImpresion;
use App\Http\Controllers\Controller;
use App\Util\redimencionarImagen;
use App\Models\Empresa\Empresa;
use Illuminate\Http\Request;
use App\Util\generales;
use Exception, DB, URL;

class EmpresaController extends Controller
{
	public function index()
	{  
        $url  = URL::to('/');
		$data = DB::table('empresa')->select('emprid','persidrepresentantelegal','emprdepaid', 'emprmuniid','emprnit','emprdigitoverificacion','emprbarrio',
                            'emprnombre','emprsigla','emprlema','emprdireccion', 'emprcorreo','emprtelefonofijo','emprtelefonocelular','emprhorarioatencion',
                            'emprurl', 'emprcodigopostal', 'emprlogo','emprpersoneriajuridica',
                            DB::raw("CONCAT(emprtelefonocelular,' ', emprtelefonofijo ) as telefonos"),
                            DB::raw("CONCAT('$url/archivos/logoEmpresa/', emprlogo ) as imagen") )->get(); 

        return response()->json(["data" => $data]);
    }

    public function datos()
	{
        $deptos     = DB::table('departamento')->select('depaid','depanombre')->OrderBy('depanombre')->get();
        $municipios = DB::table('municipio')->select('muniid','muninombre','munidepaid')->OrderBy('muninombre')->get(); 

       $jefes = DB::table('persona')->select('persid', DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre)) as nombres"),
                       DB::raw("CONCAT(persprimerapellido,' ',if(perssegundoapellido is null ,'', perssegundoapellido)) as apellidos")
                       )
                   ->whereIn('carlabid', [1, 4])->get();

        $mensajeImpresiones      = DB::table('mensajeimpresion')->select('menimpid','menimpnombre','menimpvalor')->OrderBy('menimpnombre')->get();
        $configuracionEncomienda = DB::table('configuracionencomienda')->select('conencid','conencvalorminimoenvio','conencvalorminimodeclarado','conencporcentajeseguro',
                                    'conencporcencomisionempresa', 'conencporcencomisionagencia', 'conencporcencomisionvehiculo')
                                    ->where('conencid', 1)->OrderBy('conencid')->first();

        return response()->json(["deptos" => $deptos, "municipios" => $municipios, "jefes" => $jefes,
                                "mensajeImpresiones" => $mensajeImpresiones, "configuracionEncomienda" => $configuracionEncomienda]);
    }

	public function salve(Request $request)
	{
		$this->validate(request(),[
                'jefe'               => 'required|numeric', 
                'departamento'       => 'required|numeric',
                'municipio'          => 'required|numeric',
                'nit'                => 'required|string|min:6|max:12',
                'digitoVerificacion' => 'required|numeric|max:9',
                'nombre'             => 'required|string|min:4|max:99',
                'sigla'              => 'nullable|string|min:4|max:20',
                'lema'               => 'nullable|string|min:4|max:100',
                'direccion'          => 'required|string|min:4|max:100',
                'barrio'             => 'nullable|string|min:4|max:80',
                'correo'             => 'nullable|email|min:4|max:80',
                'personeriaJuridica' => 'nullable|string|min:4|max:50',
                'telefono'           => 'nullable|max:20',
                'celular'            => 'nullable|max:20',
                'horarioAtencion'    => 'nullable|max:200',
                'url'                => 'nullable|max:100',
                'codigoPostal'       => 'nullable|max:10',
                'logo'               => 'nullable|mimes:png|max:1000'
			]);
 
        DB::beginTransaction();
		try {

            $funcion = new generales();
            if($request->hasFile('logo')){
                $rutaCarpeta    = public_path().'/archivos/logoEmpresa/';
                $file           = $request->file('logo');
                $nombreOriginal = $file->getclientOriginalName();
                $filename       = pathinfo($nombreOriginal, PATHINFO_FILENAME);
                $extension      = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                $nombreBD       = $request->nit.'_'.$funcion->quitarCaracteres($filename).'.'.$extension;
                $file->move($rutaCarpeta, $nombreBD);
                $redimencionarImagen = new redimencionarImagen();
                $redimencionarImagen->redimencionar($rutaCarpeta.'/'.$nombreBD, 160, 110);
            }else{
                $nombreBD = $request->logo_old;
            }

			$empresa                           = Empresa::findOrFail($request->codigo);
            $empresa->persidrepresentantelegal = $request->jefe;
            $empresa->emprdepaid               = $request->departamento;
			$empresa->emprmuniid               = $request->municipio;
            $empresa->emprnit                  = $request->nit;
            $empresa->emprdigitoverificacion   = $request->digitoVerificacion;
            $empresa->emprnombre               = $request->nombre;
            $empresa->emprsigla                = $request->sigla;
            $empresa->emprlema                 = $request->lema;
            $empresa->emprdireccion            = $request->direccion;
            $empresa->emprbarrio               = $request->barrio;
            $empresa->emprcorreo               = $request->correo;
            $empresa->emprpersoneriajuridica   = $request->personeriaJuridica;            
            $empresa->emprtelefonofijo         = $request->telefono;
            $empresa->emprtelefonocelular      = $request->celular;
            $empresa->emprhorarioatencion      = $request->horarioAtencion;
            $empresa->emprurl                  = $request->url;
            $empresa->emprcodigopostal         = $request->codigoPostal;
            $empresa->emprlogo                 = $nombreBD;
			$empresa->save();

            $configuracionencomienda                               = ConfiguracionEncomienda::findOrFail(1);
            $configuracionencomienda->conencvalorminimoenvio       = $request->valorMinimoEnvio;
            $configuracionencomienda->conencvalorminimodeclarado   = $request->valorMinimoDeclarado;
            $configuracionencomienda->conencporcentajeseguro       = $request->porcentajeSeguro;
            $configuracionencomienda->conencporcencomisionempresa  = $request->porcentajeComisionEmpresa;
            $configuracionencomienda->conencporcencomisionagencia  = $request->porcentajeComisionAgencia;
            $configuracionencomienda->conencporcencomisionvehiculo = $request->porcentajeComisionVehiculo;
            $configuracionencomienda->save();

            for($i = 0; $i < $request->totalCampoMensaje; $i++){
                $identificador = 'mensajeImpresionCodigo'.$i;
                $valor         = 'mensajeImpresionValor'.$i;
                $mensajeimpresion               = MensajeImpresion::findOrFail($request->$identificador);
                 $mensajeimpresion->menimpvalor  = $request->$valor;
                $mensajeimpresion->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}