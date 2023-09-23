<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificadoRequests extends FormRequest
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
            'idCD'              => 'required|numeric',
            'dependencia'       => 'required|numeric',
            'serie'             => 'required|numeric',
            'subSerie'          => 'required|numeric',
            'tipoMedio'         => 'required|numeric',
            'tipoTramite'       => 'required|numeric',
            'tipoDestino'       => 'required|numeric',

            'idCDP'             => 'required|numeric',
            'fecha'             => 'required|date|date_format:Y-m-d',
            'nombreDirigido'    => 'required|string|min:4|max:100',
            'correo'            => 'nullable|email|string|max:80|required_if:tipo_medio,2|required_if:tipo_medio,3',

            'idCDPC'           => 'required|numeric',
            'contenidoInicial' => 'required|string|min:4|max:1000',
            'tipoPersona'      => 'required|numeric',
            'tituloDocumento'  => 'nullable|string|min:4|max:200',
            'firmaPersonas'    => 'required|array|min:1',
        ];
    }
}
