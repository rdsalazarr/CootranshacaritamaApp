<?php

namespace App\Models\Persona;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table      = 'persona';
    protected $primaryKey = 'persid';
    protected $fillable   = ['carlabid','tipideid','tipperid','persdepaidnacimiento','persmuniidnacimiento','persdepaidexpedicion','persmuniidexpedicion',
                            'persdocumento','persprimernombre','perssegundonombre','persprimerapellido','perssegundoapellido','persfechanacimiento',
                            'persdireccion','perscorreoelectronico','persfechadexpedicion','persnumerotelefonofijo','persnumerocelular',
                            'persgenero','persrutafoto','persrutafirma','perstienefirmadigital','persrutacrt','persrutapem','persactiva'];
}