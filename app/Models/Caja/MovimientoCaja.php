<?php

namespace App\Models\Caja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    use HasFactory;

    protected $table      = 'movimientocaja';
    protected $primaryKey = 'movcajid';
    protected $fillable   = ['usuaid','cajaid','movcajfechahoraapertura','movcajsaldoinicial','movcajfechahoracierre',
                            'movcajsaldofinal', 'movcajcerradaautomaticamente'];
}