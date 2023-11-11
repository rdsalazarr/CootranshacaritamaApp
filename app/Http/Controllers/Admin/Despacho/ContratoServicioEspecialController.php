<?php

namespace App\Http\Controllers\Admin\Despacho;

use App\Models\Despacho\ContratoServicioEspecialConductor;
use App\Models\Despacho\ContratoServicioEspecialVehiculo;
use App\Models\Despacho\PersonaContratoServicioEspecial;
use App\Models\Despacho\ContratoServicioEspecial;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContratoServicioEspecialController extends Controller
{
    public function index()
    {  
        $data = DB::table('contratoservicioespecial as cse')->select('cse.coseesid','cse.coseesfechaincial','cse.coseesfechafinal','cse.coseesorigen','cse.coseesdestino',                                    
                                    DB::raw("CONCAT(cse.coseesanio, cse.coseesconsecutivo) as numeroContrato"),
                                    DB::raw("CONCAT(pcse.pecoseprimernombre,' ',if(pcse.pecosesegundonombre is null ,'', pcse.pecosesegundonombre),' ',
                                            pcse.pecoseprimerapellido,' ',if(pcse.pecosesegundoapellido is null ,' ', pcse.pecosesegundoapellido)) as nombreResponsable"))
                                    ->join('pecoseonacontratoservicioesp as pcse', 'pcse.pecoseid', '=', 'cse.pecoseid')
                                    ->orderBy('cse.coseesid', 'Desc')->get();
        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{
        $this->validate(request(),[
            'codigo' => 'required',
            'tipo'   => 'required'
        ]);

        $contratoservicioespecial  = [];
        $personaContrato           = [];
        $contratoVehiculo          = [];
        $contratoConductor         = [];

        $tipocontratosservicioespecial = DB::table('tipocontratoservicioespecial')->select('ticoseid','ticosenombre')->orderBy('ticosenombre')->get();
        $tipoconveniosservicioespecial = DB::table('tipoconvenioservicioespecial')->select('ticossid','ticossnombre')->orderBy('ticossnombre')->get();

        return response()->json(["tipocontratosservicioespecial" => $tipocontratosservicioespecial, "tipoconveniosservicioespecial" => $tipoconveniosservicioespecial,
                                "contratoservicioespecial"       => $contratoservicioespecial,      "personaContrato"               => $personaContrato, 
                                "contratoVehiculo"               => $contratoVehiculo,              "contratoConductor"             => $contratoConductor]);
    }

    public function salve(Request $request)
	{
        $depeid      = $request->id;
        $dependencia = ($depeid != 000) ? Dependencia::findOrFail($depeid) : new Dependencia();

	    $this->validate(request(),[
	   			'codigo'          => 'required|string|min:1|max:10|unique:dependencia,depecodigo,'.$dependencia->depeid.',depeid',
                'sigla'           => 'required|string|min:1|max:3|unique:dependencia,depesigla,'.$dependencia->depeid.',depeid',
	   	        'nombre'          => 'required|string|min:4|max:80',
                'correo'          => 'required|string|email|min:4|max:80',
                'jefeDependencia' => 'required',
	            'estado'          => 'required',
                'subSeries'       => 'required|array|min:1',
                'personas'        => 'required|array|min:1'
	        ]);

        DB::beginTransaction();
        try {
            $dependencia->depejefeid = $request->jefeDependencia;
            $dependencia->depecodigo = $request->codigo;
            $dependencia->depesigla  = mb_strtoupper($request->sigla,'UTF-8');
            $dependencia->depenombre = mb_strtoupper($request->nombre,'UTF-8');
            $dependencia->depecorreo = $request->correo;
            $dependencia->depeactiva = $request->estado;
            $dependencia->save();

            if($request->tipo === 'I'){
				//Consulto el ultimo identificador de la dependencia
				$dependenciaConsecutivo    = Dependencia::latest('depeid')->first();
				$depeid                  = $dependenciaConsecutivo->depeid;
			}

            foreach($request->subSeries as $subserie){
				$identificador = $subserie['identificador'];
				$subSerie      = $subserie['subSerie'];
				$personaEstado = $subserie['estado'];
				if($personaEstado === 'I'){
					$dependenciasubseriedocumental = new DependenciaSubSerieDocumental();
					$dependenciasubseriedocumental->desusddepeid   = $depeid;
					$dependenciasubseriedocumental->desusdsusedoid = $subSerie;
					$dependenciasubseriedocumental->save();
				}else if($personaEstado === 'D'){
					$dependenciasubseriedocumental = DependenciaSubSerieDocumental::findOrFail($identificador);
					$dependenciasubseriedocumental->delete();
				}else{
					$dependenciasubseriedocumental = DependenciaSubSerieDocumental::findOrFail($identificador);
					$dependenciasubseriedocumental->desusddepeid   = $depeid;
					$dependenciasubseriedocumental->desusdsusedoid = $subSerie;
					$dependenciasubseriedocumental->save();
				}
			}

            foreach($request->personas as $dataPersona){
				$identificador = $dataPersona['identificador'];
				$persona       = $dataPersona['persona'];
				$personaEstado = $dataPersona['estado'];
				if($personaEstado === 'I'){
					$dependenciapersona = new DependenciaPersona();
					$dependenciapersona->depperdepeid  = $depeid;
					$dependenciapersona->depperpersid  = $persona;
					$dependenciapersona->save();
				}else if($personaEstado === 'D'){
					$dependenciapersona = DependenciaPersona::findOrFail($identificador);
					$dependenciapersona->delete();
				}else{
					$dependenciapersona = DependenciaPersona::findOrFail($identificador);
					$dependenciapersona->depperdepeid  = $depeid;
					$dependenciapersona->depperpersid  = $persona;
					$dependenciapersona->save();
				}
			}
            DB::commit();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
            DB::rollback();
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}
}