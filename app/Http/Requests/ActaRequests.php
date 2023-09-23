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
            'idCD'               => 'required|numeric',
            'dependencia'        => 'required|numeric',
            'serie'              => 'required|numeric',
            'subSerie'           => 'required|numeric',
            'tipoMedio'          => 'required|numeric',
            'tipoTramite'        => 'required|numeric',
            'tipoDestino'        => 'required|numeric',

            'idCDP'              => 'required|numeric',
            'fecha'              => 'required|date|date_format:Y-m-d',
            'correo'             => 'nullable|string|min:4|max:1000|required_if:tipoMedio,2|required_if:tipoMedio,3',
            'contenido'          => 'required',

            'idCDPA'            => 'required|numeric',
            'tipoActa'          => 'required|numeric',
            'horaInicial'       => 'required|date_format:H:i',
            'horaFinal'         => 'required|date_format:H:i|after:horaInicial',
            'lugar'             => 'required|string|min:4|max:200',
            'convocatoria'      => 'required|numeric',
            'asistentes'        => 'required|string|min:4|max:4000',
            'invitados'         => 'nullable|string|min:4|max:4000',
            'ausentes'          => 'nullable|string|min:4|max:4000',
            'ordenDia'          => 'required|string|min:4|max:4000',
            'quorum'            => 'required|string|min:4|max:200', 
            'convocatoriaLugar' => 'nullable|string|min:4|max:100|required_if:convocatoria,1',
            'convocatoriaFecha' => 'nullable|date_format:Y-m-d|required_if:convocatoria,1',
            'convocatoriaHora'  => 'nullable|date_format:H:i|required_if:convocatoria,1',
            'firmaPersonas'     => 'required|array|min:1'
        ];
    }
}