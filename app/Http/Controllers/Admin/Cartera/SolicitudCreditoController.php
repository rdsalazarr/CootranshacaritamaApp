<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Models\Cartera\SolicitudCreditoCambioEstado;
use App\Models\Cartera\SolicitudCreditoDesembolso;
use App\Models\Cartera\SolicitudCredito;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Util\generarPdf;
use App\Util\notificar;
use App\Util\generales;
use Exception, DB;
use Carbon\Carbon;

class SolicitudCreditoController extends Controller
{
    public function index()
    {
        $data = DB::table('lineacredito')->select('lincreid','lincrenombre','lincreporcentaje','lincreactiva',
                                    DB::raw("if(lincreactiva = 1 ,'Sí', 'No') as estado"))
                                    ->orderBy('ticavenombre')->get();
        return response()->json(["data" => $data]);
    }
}
