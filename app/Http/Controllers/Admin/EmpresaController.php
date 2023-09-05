<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Util\generales;
use DB, URL;

class EmpresaController extends Controller
{  
	public function index()
	{  
        $url = URL::to('/');
		$data = DB::table('empresa')->select('emprid','emprdepaid', 'emprmuniid','emprnit','emprnombre','emprsigla','emprlema','emprdireccion',
                            'emprcorreo','emprtelefonofijo','emprhorarioatencion','emprurl','emprcodigopostal',
                            'emprlogo','emprtelefonocelular','emprdocumentorepresenlegal','emprfirmapresenlegal',
                            'emprrepresentantelegal','emprcargorepresentantelegal','emprciudadresidenciareplegal','emprlugarexpedicionreplegal',
                            DB::raw("CONCAT(emprtelefonocelular,' ', emprtelefonofijo ) as telefonos"),
                            DB::raw("CONCAT('$url/images/logo/', emprlogo ) as imagen"),
                            DB::raw("CONCAT('$url/images/firma/', emprfirmapresenlegal ) as firma")
                            )->get();  
                               
        return response()->json(["data" => $data]);  
    }
    
    public function municipio()
	{ 
        $deptos =  DB::table('departamento')
                       ->select('depaid','depanombre')    
                       ->OrderBy('depanombre')->get(); 

        $municipios =  DB::table('municipio')
                       ->select('muniid','muninombre','munidepaid')    
                       ->OrderBy('muninombre')->get(); 

        return response()->json(["deptos" => $deptos, "municipios" => $municipios]);  
    }

	public function salve(Request $request)
	{ 
		$this->validate(request(),[
                'nit'       => 'required',
                'nombre'    => 'required|string|min:4|max:99',
                'direccion' => 'required',
                'correo'    => 'required|email|min:4|max:80',
                'representanteLegal'                 => 'nullable|max:100',
                'cargoRepresentanteLegal'            => 'nullable|max:50',
                'ciudadResidenciaRepresentanteLegal' => 'nullable|max:50',
                'lugarExpedicionRepresentanteLegal'  => 'nullable|max:50',
                'documentoRepresentanteLegal'        => 'nullable|max:15',
               // 'logo'       => 'nullable|mimes:png|max:1000',
              //  'firma'      => 'nullable|mimes:png|max:1000'
			  ]); 

        dd($request, $request->hasFile('logo'), $request->hasFile('firma'));
        
		try {
            $funcion = new generales();
            if($request->hasFile('logo')){
                $file = $request->file('logo');
                $nombreOriginal = $file->getclientOriginalName();
                $filename    = pathinfo($nombreOriginal, PATHINFO_FILENAME);
                $extension   = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                $nombreBD    = $request->nit.'_'.$funcion->quitarCaracteres($filename).'.'.$extension;
                $file->move(public_path().'/images/logo/',$nombreBD);
            }else{
                $nombreBD = $request->logo_old;
            }
            
            if($request->hasFile('firma')){
                $file = $request->file('firma');
                $nombreOriginal = $file->getclientOriginalName();
                $filename      = pathinfo($nombreOriginal, PATHINFO_FILENAME);
                $extension     = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
                $nombreBDFirma = $request->nit.'_'.$funcion->quitarCaracteres($filename).'.'.$extension;
                $file->move(public_path().'/images/firma/',$nombreBD);
            }else{
                $nombreBDFirma = $request->firma_old;
            } 

			$empresa = Empresa::findOrFail($request->codigo);
            $empresa->emprdepaid = $request->departamento;
			$empresa->emprmuniid = $request->municipio;
            $empresa->emprnit    = $request->nit;
            $empresa->emprnombre = $request->nombre;
            $empresa->emprsigla  = $request->sigla;
            $empresa->emprlema   = $request->lema;
            $empresa->emprurl    = $request->url;
            $empresa->emprdireccion = $request->direccion;
            $empresa->emprcorreo    = $request->correo;
            $empresa->emprtelefonofijo    = $request->telefono;
            $empresa->emprtelefonocelular = $request->celular;
            $empresa->emprhorarioatencion = $request->horarioAtencion; 
            $empresa->emprurl             = $request->url; 
            $empresa->emprcodigopostal             = $request->codigo_postal;
            $empresa->emprdocumentorepresenlegal   = $request->documentoRepresentanteLegal;
            $empresa->emprrepresentantelegal       = $request->representanteLegal;
            $empresa->emprcargorepresentantelegal  = $request->cargoRepresentanteLegal;
            $empresa->emprciudadresidenciareplegal = $request->ciudadResidenciaRepresentanteLegal;
            $empresa->emprlugarexpedicionreplegal  = $request->lugarExpedicionRepresentanteLegal;
            $empresa->emprfirmapresenlegal         = $nombreBDFirma;
            $empresa->emprlogo = $nombreBD;
			$empresa->save();
            return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}
