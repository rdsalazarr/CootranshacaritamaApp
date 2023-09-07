<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dependencia extends Model
{
    use HasFactory;

    protected $table      = 'dependencia';
    protected $primaryKey = 'depeid';
    protected $fillable   = ['depejefeid','depecodigo','depesigla','depenombre','depecorreo','depeactiva'];
}