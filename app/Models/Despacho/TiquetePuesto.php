<?php

namespace App\Models\Despacho;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiquetePuesto extends Model
{
    use HasFactory;

    protected $table      = 'tiquetepuesto';
    protected $primaryKey = 'tiqpueid';
    protected $fillable   = ['tiquid','tiqpuenumeropuesto'];
}