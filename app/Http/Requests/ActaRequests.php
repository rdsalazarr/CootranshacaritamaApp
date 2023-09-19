<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActaRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'subSerie'          => 'required',
            'fecha'             => 'required|date|date_format:Y-m-d',
            'tipoMedio'         => 'required',
            'correo'            => 'required_if:tipo_medio,2|required_if:tipo_medio,3|email',  
            'tipoTramite'       => 'required',
            'horaInicio'        => 'required|date_format:H:i',
            'horaFinal'         => 'required|date_format:H:i|after:hora_inicio',
            'lugar'             => 'required',
            'tipoActa'          => 'required',
            'asistentes'        => 'required',
            'ordenDia'          => 'required',
            'desarrollo'        => 'required',
            'convocatoria'      => 'required',
            'convocatoriaLugar' => 'required_if:convocatoria,1',
         //   'convocatoriaFecha' => 'required_if:convocatoria,1|date|date_format:Y-m-d', 
         //   'convocatoriaHora'  => 'required_if:convocatoria,1|date_format:H:i', 
            'nombreRemitente'   => 'required|array|min:1'
        ];
    }
}
