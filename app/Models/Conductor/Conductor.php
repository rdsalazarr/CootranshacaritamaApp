<?php

namespace App\Models\Conductor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    use HasFactory;

    protected $table      = 'conductor';
    protected $primaryKey = 'condid';
    protected $fillable   = ['persid','tiescoid','codufechaingreso'];
}