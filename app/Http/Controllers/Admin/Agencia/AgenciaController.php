<?php

namespace App\Http\Controllers\Admin\Agencia;

use App\Http\Controllers\Controller;
use App\Models\Agencia\Agencia;
use Illuminate\Http\Request;
use Exception, DB;

class AgenciaController extends Controller
{
    public function index()
    {
        try{
            $data = DB::table('agencia as a')->select('a.agenid','a.persidresponsable', 'a.agendepaid','a.agenmuniid', 'a.agennombre','a.agendireccion','a.agencorreo',
                                        'a.agentelefonocelular','a.agentelefonofijo','a.agenactiva',
                                    DB::raw("if(a.agenactiva = 1 ,'Sí', 'No') as estado"),
                                    DB::raw("CONCAT(a.agentelefonocelular,' ',if(a.agentelefonofijo is null ,'', CONCAT(' - ',a.agentelefonofijo))) as telefonos"),
                                    DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ', p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as responsable"))
                                    ->join('persona as p', 'p.persid', '=', 'a.persidresponsable')
                                    ->orderBy('a.agennombre')->get();

            return response()->json(['success' => true, "data" => $data]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'data' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }

    public function datos()
	{
        try{
            $deptos     =  DB::table('departamento')->select('depaid','depanombre')->OrderBy('depanombre')->get();
            $municipios =  DB::table('municipio')->select('muniid','muninombre','munidepaid')->OrderBy('muninombre')->get();
            
            $personas   = DB::table('persona')->select('persid', DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre)) as nombres"),
                                DB::raw("CONCAT(persprimerapellido,' ',if(perssegundoapellido is null ,'', perssegundoapellido)) as apellidos"))
                                ->whereNotIn('persid', [1])
                                ->whereIn('carlabid', [1, 2])->get();

            return response()->json(["deptos" => $deptos, "municipios" => $municipios, "responsables" => $personas]);  
        }catch(Exception $e){
            return response()->json(['success' => false, 'data' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }    

    public function salve(Request $request)
	{
        $id      = $request->codigo;
        $agencia = ($id != 000) ? Agencia::findOrFail($id) : new Agencia();

	    $this->validate(request(),[
                'responsable'     => 'required|numeric',
                'departamento'    => 'required|numeric',
                'municipio'       => 'required|numeric',
                'nombre'          => 'required|string|min:4|max:100',
                'direccion'       => 'required|string|min:4|max:100',
                'correo'          => 'required|email|string|max:80',
                'telefonoCelular' => 'nullable|string|max:20',
                'telefonoFijo'    => 'nullable|string|max:20',
                'estado'          => 'required'
	        ]);

        try {
            $agencia->persidresponsable   = $request->responsable;
            $agencia->agendepaid          = $request->departamento;
            $agencia->agenmuniid          = $request->municipio;       
            $agencia->agennombre          = mb_strtoupper($request->nombre,'UTF-8');
            $agencia->agendireccion       = $request->direccion;
            $agencia->agencorreo          = $request->correo;
            $agencia->agentelefonocelular = $request->telefonoCelular;
            $agencia->agentelefonofijo    = $request->telefonoFijo;
            $agencia->agenactiva          = $request->estado;
            $agencia->save();
        	return response()->json(['success' => true, 'message' => 'Registro almacenado con éxito']);
		} catch (Exception $error){
			return response()->json(['success' => false, 'message'=> 'Ocurrio un error en el registro => '.$error->getMessage()]);
		}
	}

    public function destroy(Request $request)
	{
		$vehiculo = DB::table('vehiculo')->select('agenid')->where('agenid', $request->codigo)->first();
		if($vehiculo){
			return response()->json(['success' => false, 'message'=> 'Este registro no se puede eliminar, porque está asignado a un vehículo del sistema']);
		}else{
			try {
				$agencia = Agencia::findOrFail($request->codigo);
				$agencia->delete();
				return response()->json(['success' => true, 'message' => 'Registro eliminado con éxito']);
			} catch (Exception $error){
				return response()->json(['success' => false, 'message'=> 'Ocurrio un error en la eliminación => '.$error->getMessage()]);
			}
		}
	}
}