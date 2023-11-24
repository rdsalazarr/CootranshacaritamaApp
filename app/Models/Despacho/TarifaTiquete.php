<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifaTiquete extends Model
{
    use HasFactory;

    protected $table      = 'tarifatiquete';
    protected $primaryKey = 'tartiqid';
    protected $fillable   = ['rutaid','depaiddestino','muniiddestino','tartiqvalor','tartiqfondoreposicion'];
}