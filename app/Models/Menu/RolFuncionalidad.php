<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolFuncionalidad extends Model
{
    use HasFactory;
    
    public $timestamps    = false;
    protected $table      = 'rolfuncionalidad';   
    protected $primaryKey = 'rolfunid';
    protected $fillable   = ['rolfunrolid','rolfunfuncid'];
}