<?php

namespace App\Models\Dependencia;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DependenciaSubSerieDocumental extends Model
{
    use HasFactory;

    protected $table      = 'dependenciasubseriedocumental';
    public $timestamps    = false;
    protected $primaryKey = 'desusdid';
    protected $fillable   = ['desusdsusedoid','desusddepeid'];
}