<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DependenciaSubSerieDocumental;
use App\Models\DependenciaPersona;
use App\Models\Dependencia;
use Exception, DB;

class DependenciaController extends Controller
{
    public function index()
    {  
        $data = DB::table('dependencia as d')->select('d.depeid','d.depejefeid','d.depecodigo','d.depesigla','d.depenombre',
                                    'd.depecorreo','d.depeactiva',
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                            p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreJefe"),
                                    DB::raw("if(d.depeactiva = 1 ,'Sí', 'No') as estado"))
                                    ->join('persona as p', 'p.persid', '=', 'd.depejefeid')
                                    ->orderBy('d.depenombre')->get();
        return response()->json(["data" => $data]);
    }

    public function datos(Request $request)
	{ 
        $this->validate(request(),[
            'codigo' => 'required',
            'tipo'  => 'required|max:1'
        ]);

		$personas = DB::table('persona')
                        ->select('persid','carlabid',DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre),' ',
                        persprimerapellido,' ',if(perssegundoapellido is null ,' ', perssegundoapellido)) as nombrePersona"))
                        ->where('persactiva',1)
                        ->orderBy('persprimernombre')->orderBy('perssegundonombre')
                        ->orderBy('persprimerapellido')->orderBy('perssegundoapellido')->get();

        $seriesdocumentales    = DB::table('seriedocumental')->select('serdocid','serdocnombre')->where('serdocactiva', 1)->orderBy('serdocnombre')->get();        
        $subseriesdocumentales = DB::table('subseriedocumental')->select('susedoid','serdocid','susedonombre')->where('susedoactiva', 1)->orderBy('susedonombre')->get();

        $dependenciasubseriedocumentales = [];
        $dependenciapersonas             = [];
        if($request->tipo === 'U'){
            $dependenciasubseriedocumentales    = DB::table('dependenciasubseriedocumental as dssd')
                                                ->select('dssd.desusdid','dssd.desusdsusedoid','dssd.desusddepeid','ssd.susedonombre','sd.serdocnombre')
                                                ->join('subseriedocumental as ssd', 'ssd.susedoid', '=', 'dssd.desusdsusedoid')
                                                ->join('seriedocumental as sd', 'sd.serdocid', '=', 'ssd.serdocid')
                                                ->where('dssd.desusddepeid', $request->codigo )
                                                ->orderBy('sd.serdocnombre')->orderBy('ssd.susedonombre')->get();

            $dependenciapersonas    = DB::table('dependenciapersona as dp')
                                                ->select('dp.depperid','dp.depperdepeid','dp.depperpersid',DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                                p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                                ->join('persona as p', 'p.persid', '=', 'dp.depperpersid')
                                                ->where('dp.depperdepeid', $request->codigo )
                                                ->orderBy('nombrePersona')->get();  
        }

        return response()->json(["personas" => $personas,"seriesdocumentales" => $seriesdocumentales ,"subseriesdocumentales" => $subseriesdocumentales, 
                                "dependenciasubseriedocumentales" => $dependenciasubseriedocumentales, "dependenciapersonas" => $dependenciapersonas, ]);
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

    public function destroy(Request $request)
	{
		$codigodocumental = DB::table('codigodocumental')
                                ->select('depeid')
                                ->where('depeid', $request->codigo)->first();

		if($codigodocumental){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un tipo documental producido en el sistema']);
		}else{
            DB::beginTransaction();
			try {
                $dependencia = Dependencia::findOrFail($request->codigo);
                if ($dependencia->has('dependenciaPersona')){ 
                    foreach ($dependencia->dependenciaPersona as $idDependencia){
                        $dependencia->dependenciaPersona()->delete($idDependencia);
                    }
                }
                if ($dependencia->has('dependenciaSubSerieDocumental')){ 
                    foreach ($dependencia->dependenciaSubSerieDocumental as $idDependencia){
                        $dependencia->dependenciaSubSerieDocumental()->delete($idDependencia);
                    }
                }
				$dependencia->delete();
                DB::commit();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
                DB::rollback();
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}