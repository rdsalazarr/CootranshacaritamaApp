<?php

namespace App\Models\Series;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSerieDocumental extends Model
{
    use HasFactory;
    
    protected $table      = 'subseriedocumental';
    protected $primaryKey = 'susedoid';
    protected $fillable   = ['serdocid','tipdocid','susedocodigo','susedonombre',
                            'susedopermiteeliminar','susedoactiva'];
}