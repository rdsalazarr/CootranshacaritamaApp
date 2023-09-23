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
            'idCD'                  => 'required|numeric',
            'dependencia'           => 'required|numeric',
            'serie'                 => 'required|numeric',
            'subSerie'              => 'required|numeric',
            'tipoMedio'             => 'required|numeric',
            'tipoTramite'           => 'required|numeric',
            'tipoDestino'           => 'required|numeric',

            'idCDP'                 => 'required|numeric',
            'fecha'                 => 'required|date|date_format:Y-m-d',
            'destinatarios'        => 'required|string|min:4|max:2000',
            'asunto'                => 'required|string|min:4|max:200',
            'correo'                => 'nullable|string|min:4|max:1000|required_if:tipo_medio,2|required_if:tipo_medio,3',
            'contenido'             => 'required',
            'tieneAnexo'            => 'required|numeric',
            'nombreAnexo'           => 'nullable|string|min:4|max:300',
            'tieneCopia'            => 'required|numeric',
            'nombreCopia'           => 'nullable|string|min:4|max:300',

            'idCDPC'                => 'required|numeric',
            'despedida'             => 'required|numeric',
            'firmaPersonas'         => 'required|array|min:1',
            'archivos'              => 'nullable|array|max:2000',
            //'archivos.*'            => 'file|mimetypes:application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:2000',
            'archivos.*'            => 'nullable|mimes:jpg,png,jpeg,doc,docx,pdf,ppt,pptx,xls,xlsx,xlsm,zip,rar|max:2000'  
        ];
    }
}
