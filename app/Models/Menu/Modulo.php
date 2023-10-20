<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $table      = 'modulo';
    protected $primaryKey = 'moduid';
    protected $fillable   = ['modunombre','moduicono','moduorden','moduactivo'];
}