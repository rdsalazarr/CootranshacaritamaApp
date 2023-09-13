<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DependenciaPersona extends Model
{
    use HasFactory;

    protected $table      = 'dependenciapersona';
    public $timestamps    = false;
    protected $primaryKey = 'depperid';
    protected $fillable   = ['depperdepeid','depperpersid'];
}