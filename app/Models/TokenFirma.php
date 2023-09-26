<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenFirma extends Model
{
    use HasFactory;

    protected $table      = 'tokenfirma';  
    protected $primaryKey = 'tokfirid';
    protected $fillable   = ['tokfirtoken','tokfirfechahoranotificacion','tokfirfechahoramaxvalidez',
                             'tokfirmsjcorreo','tokfirmsjcelular','solfirmediocelularverificacion'];
}