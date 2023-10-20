<?php

namespace App\Http\Controllers\Admin\Festivo;

use App\Http\Controllers\Controller;
use App\Models\Festivo\Festivo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception, DB;

class FestivoController extends Controller
{
    public function index()
    {
        $data = DB::table('festivo')->select('festid','festfecha')->whereDate('festfecha', '>=', Carbon::now()->format('Y-m-d'))
				        ->orderBy('festfecha')->get();

        return response()->json(["data" => $data]);
    }

    public function salve(Request $request)
    {
        $this->validate(request(),['fechas' => 'required|array|min:1' ]);
        DB::beginTransaction();
        try {

           foreach ($request->fechas as $dataFecha)
            {
                $festid = $dataFecha['codigo'];
                $fecha  = $dataFecha['fecha']; 
                $estado = $dataFecha['estado'];

                if($estado == 'I'){
                    $festivo = new Festivo();
                    $festivo->festfecha = $fecha;
                    $festivo->save();
                }else if($estado == 'D'){
                    Festivo::findOrFail($festid)->delete();
                }else{
                    $festivo =Festivo::findOrFail($festid);
                    $festivo->festfecha = $fecha;
                    $festivo->save(); 
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