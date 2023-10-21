<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonaRequests extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        //dd($this->input('fechaIngreso'));

        return [
            'documento'              => 'required|string|max:15|unique:persona,persdocumento,'. $this->input('codigo').',persid,tipideid,'.$this->input('tipoIdentificacion'), 
            'cargo'                  => 'required|numeric',
            'tipoIdentificacion'     => 'required|numeric',
            'tipoRelacionLaboral'    => 'required|numeric',
            'departamentoNacimiento' => 'required|numeric',
            'municipioNacimiento'    => 'required|numeric',
            'departamentoExpedicion' => 'required|numeric',
            'municipioExpedicion'    => 'required|numeric',
            'primerNombre'           => 'required|string|min:4|max:40',
            'segundoNombre'          => 'nullable|string|min:4|max:40',
            'primerApellido'         => 'required|string|min:4|max:40',
            'segundoApellido'        => 'nullable|string|min:4|max:40',
            'fechaNacimiento' 	     => 'nullable|date|date_format:Y-m-d',
            'direccion'              => 'required|string|min:4|max:100',
            'correo'                 => 'nullable|email|string|max:80',
            'fechaExpedicion' 	     => 'nullable|date|date_format:Y-m-d',
            'telefonoFijo'           => 'nullable|string|max:20',
            'numeroCelular'          => 'nullable|string|max:20',
            'genero'                 => 'required',
            'estado'                 => 'required',
            'firmaDigital'           => 'required',
            'firma' 	             => 'nullable|mimes:png,PNG|max:1000',
            'fotografia'             => 'nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG|max:1000',
            'claveCertificado'       => 'nullable|string|max:20',
            'fechaIngresoAsociado'   => 'nullable|date_format:Y-m-d|required_if:formulario,ASOCIADO',
            'fechaIngresoConductor'  => 'nullable|date_format:Y-m-d|required_if:formulario,CONDUCTOR' 
        ];
    }
}