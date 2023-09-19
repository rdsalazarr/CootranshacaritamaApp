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
            'subSerie'         => 'required',
            'fecha'            => 'required|date|date_format:Y-m-d',
            'tipoMedio'        => 'required',
            'tipoDestino'      => 'required',
            'correo'           => 'required_if:tipo_medio,2|required_if:tipo_medio,3|email',
            'tipoTramite'      => 'required',
            'titulo'           => 'required',
            'tipoPersona'      => 'required',
            'dirigidoA'        => 'required',
            'contenido'        => 'required',
            'nombreRemitente'  => 'required',
            'cargoRemitente'   => 'required',
            'depeidproductora' => 'required',
            'depeproductora'   => 'required'
        ];
    }
}
