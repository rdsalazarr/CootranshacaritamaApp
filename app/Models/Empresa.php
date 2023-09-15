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
    protected $fillable   = ['persidrepresentantelegal','emprdepaid','emprmuniid','emprnit','emprdigitoverificacion',
                            'emprnombre','emprsigla','emprlema','emprdireccion', 'emprcorreo','emprtelefonofijo','emprtelefonocelular',
                            'emprhorarioatencion','emprurl','emprcodigopostal', 'emprlogo'];

}
