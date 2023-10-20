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
        $data = DB::table('agencia')->select('agenid','persidresponsable', 'agendepaid','agenmuniid', 'agennombre','agendireccion','agencorreo',
                                        'agentelefonocelular','agentelefonofijo','agenactiva',
                                    DB::raw("if(agenactiva = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('agenactiva')->get();
        return response()->json(["data" => $data]);
    }

    public function datos()
	{
        $deptos =  DB::table('departamento')->select('depaid','depanombre')->OrderBy('depanombre')->get();
        $municipios =  DB::table('municipio')->select('muniid','muninombre','munidepaid')->OrderBy('muninombre')->get(); 
        
       $personas = DB::table('persona')->select('persid', DB::raw("CONCAT(persprimernombre,' ',if(perssegundonombre is null ,'', perssegundonombre)) as nombres"),
                       DB::raw("CONCAT(persprimerapellido,' ',if(perssegundoapellido is null ,'', perssegundoapellido)) as apellidos")
                       )
                   ->whereIn('carlabid', [1, 2])->get();

        return response()->json(["deptos" => $deptos, "municipios" => $municipios, "responsables" => $personas]);  
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