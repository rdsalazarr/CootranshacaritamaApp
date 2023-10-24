<?php

namespace App\Models\Conductor;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConductorVehiculo extends Model
{
    use HasFactory;

    protected $table      = 'conductorvehiculo';
    protected $primaryKey = 'convehid';
    protected $fillable   = ['condid','vehiid'];
}