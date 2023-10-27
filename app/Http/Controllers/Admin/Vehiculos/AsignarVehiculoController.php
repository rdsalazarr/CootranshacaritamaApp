<?php

namespace App\Http\Controllers\Admin\Vehiculos;

use App\Models\Conductor\ConductorVehiculo;
use App\Models\Asociado\AsociadoVehiculo;
use App\Models\Vehiculos\VehiculoPoliza;
use App\Models\Vehiculos\VehiculoSoat;
use App\Models\Vehiculos\VehiculoCrt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class AsignarVehiculoController extends Controller
{

    
    public function listAsociados()
    {
        $data = DB::table('persona as p')->select('a.asocid','p.persdocumento',
                                DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombrePersona"))
                                ->join('asociado as a', 'a.persid', '=', 'p.persid')
                                ->where('p.persactiva', true)
                                ->orderBy('p.persprimernombre')->orderBy('p.perssegundonombre')
                                ->orderBy('p.persprimerapellido')->orderBy('p.perssegundoapellido')->get();
                                
        return response()->json(["data" => $data]);
    }
}