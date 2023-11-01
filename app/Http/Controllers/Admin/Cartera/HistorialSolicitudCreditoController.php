<?php

namespace App\Http\Controllers\Admin\Cartera;

use App\Http\Controllers\Controller;
use Exception, Auth, DB, URL;
use Illuminate\Http\Request;

class HistorialSolicitudCreditoController extends Controller
{
    public function index()
    {
        $data = DB::table('solicitudcredito as sc')
                        ->select('sc.solcreid', 'p.persid', 'sc.solcrefechasolicitud','sc.solcredescripcion','sc.solcrenumerocuota',
                        DB::raw("CONCAT('$ ', FORMAT(sc.solcrevalorsolicitado, 0)) as valorSolicitado"),'lc.lincrenombre as lineaCredito',
                        DB::raw("CONCAT( p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',
                        p.persprimerapellido,' ',if(p.perssegundoapellido is null ,' ', p.perssegundoapellido)) as nombreAsociado"))
                        ->join('asociado as a', 'a.asocid', '=', 'sc.asocid')
                        ->join('persona as p', 'p.persid', '=', 'a.persid')
                        ->join('lineacredito as lc', 'lc.lincreid', '=', 'sc.lincreid')
                        ->orderBy('sc.solcreid')->get();

        return response()->json(["data" => $data]);
    }
}