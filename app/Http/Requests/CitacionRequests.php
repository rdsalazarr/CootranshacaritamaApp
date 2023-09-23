<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitacionRequests extends FormRequest
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
            'idCD'          => 'required|numeric',
            'dependencia'   => 'required|numeric',
            'serie'         => 'required|numeric',
            'subSerie'      => 'required|numeric',
            'tipoMedio'     => 'required|numeric',
            'tipoTramite'   => 'required|numeric',
            'tipoDestino'   => 'required|numeric',

            'idCDP'         => 'required|numeric',
            'fecha'         => 'required|date|date_format:Y-m-d',
            'correo'        => 'nullable|string|min:4|max:1000|required_if:tipoMedio,2|required_if:tipoMedio,3',
            'contenido'     => 'required',

            'idCDPC'        => 'required|numeric',
            'tipoCitacion'  => 'required|numeric',
            'horaInicial'   => 'required|date_format:H:i',
            'lugar'         => 'required|string|min:4|max:200',
            'firmaPersonas' => 'required|array|min:1'
        ];
    }
}
