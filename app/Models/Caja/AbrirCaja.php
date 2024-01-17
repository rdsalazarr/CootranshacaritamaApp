<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbrirCaja extends Model
{
    use HasFactory;

    protected $table      = 'abrircaja';
    protected $primaryKey = 'abrcajid';
    protected $fillable   = ['usuaid','cajaid','abrcajfechahoraapertura','abrcajsaldoinicial','abrcajfechahoracierre','abrcajsaldofinal',
                            'abrcajcerradaautomaticamente'];
}