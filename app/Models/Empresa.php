<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    
    protected $table      = 'empresa';
    public $timestamps    = false;
    protected $primaryKey = 'emprid';
    protected $fillable   = ['emprdepaid','emprmunid','emprnit','emprnombre','emprsigla','emprlema','emprdireccion',
                            'emprcorreo','emprtelefonofijo','emprhorarioatencion','emprurl','emprcodigopostal',
                            'emprlogo','emprtelefonocelular','emprdocumentorepresenlegal', 'emprfirmapresenlegal',
                            'emprrepresentantelegal','emprcargorepresentantelegal',
                            'emprciudadresidenciareplegal','emprlugarexpedicionreplegal'];

    //Funcion para pasar la informacion en todas las vistas
    public static function consultar()
    {
        return DB::table('empresa as e')
                    ->select('e.*','d.depanombre', 
                        DB::raw("CONCAT('/images/intitucion/',e.emprlogo) as logo") 
                        )   
                    ->leftjoin('municipio as m', 'm.muniid', '=', 'e.emprmunid') 
                    ->leftjoin('departamento as d', 'd.depaid', '=', 'm.munidepaid')  
                    ->where('e.emprid', 1)->first();
    }
}
