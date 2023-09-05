<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table      = 'municipio';
    protected $primaryKey = 'muniid';
    protected $fillable   = ['munidepaid','municodigo','muninombre','munihacepresencia'];
}
