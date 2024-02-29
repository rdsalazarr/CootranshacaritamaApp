<?php

namespace App\Http\Controllers\Admin\Informes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception, DB;

class InformeDescargableController extends Controller
{
    public function index()
    {
        try{
            $colocaciones =   DB::table('colocacion as c')
                                    ->select('c.coloid',
                                    DB::raw("CONCAT(p.persprimernombre,' ',IFNULL(p.perssegundonombre,''),' ',p.persprimerapellido,' ',IFNULL(p.perssegundoapellido,''), ' ', c.coloanio, c.colonumerodesembolso) as nombrePersona"))
                                    ->join('solicitudcredito as sc', 'sc.solcreid', '=', 'c.solcreid')
                                    ->join('persona as p', 'p.persid', '=', 'sc.persid')
                                    ->orderBy('c.coloanio')
                                    ->orderBy('c.colonumerodesembolso')
                                    ->get();

            return response()->json(['success' => true, "colocaciones" => $colocaciones]);
        }catch(Exception $e){
            return response()->json(['success' => false, 'message' => 'Error al obtener la información => '.$e->getMessage()]);
        }
    }
}