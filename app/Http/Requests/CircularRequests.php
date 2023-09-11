<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CircularRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'destinatarios'    => 'required',
            'tipoTramite'      => 'required',
            'asunto'           => 'required',
            'tipoMedio'        => 'required',
            'correo'           => 'required_if:tipo_medio,2|required_if:tipo_medio,3|email',
            'contenido'        => 'required',
            'despedida'        => 'required',
            'enviarCopia'      => 'required',
            'anexarDocumento'  => 'required',
            'depeidproductora' => 'required',
            'depeproductora'   => 'required',
            'nombreRemitente'  => 'required|array|min:1'
        ];
    }
}
