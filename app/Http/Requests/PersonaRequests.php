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
        return [
            'documento'              => 'required|string|max:15|unique:persona,persdocumento,'. $this->input('codigo').',persid,tipideid,'.$this->input('tipoIdentificacion'),
            'cargo'                  => 'required|numeric',
            'tipoIdentificacion'     => 'required|numeric',
            'tipoPersona'            => 'required|string',
            'departamentoNacimiento' => 'required|numeric',
            'municipioNacimiento'    => 'required|numeric',
            'departamentoExpedicion' => 'required|numeric',
            'municipioExpedicion'    => 'required|numeric',
            'primerNombre'           => 'required|string|min:3|max:40',
            'segundoNombre'          => 'nullable|string|min:3|max:40',
            'primerApellido'         => 'required|string|min:4|max:40',
            'segundoApellido'        => 'nullable|string|min:4|max:40',
            'fechaNacimiento' 	     => 'nullable|date|date_format:Y-m-d',
            'direccion'              => 'required|string|min:4|max:100',
            'correo'                 => 'nullable|email|string|max:80|required_if:firmaElectronica,1', 
            'fechaExpedicion' 	     => 'nullable|date|date_format:Y-m-d',
            'telefonoFijo'           => 'nullable|string|max:20',
            'numeroCelular'          => 'nullable|string|max:20',
            'genero'                 => 'required',
            'estado'                 => 'required',
            'firmaElectronica'       => 'required',
            'firmaDigital'           => 'required',
            'firma' 	             => 'nullable|mimes:png,PNG|max:1000',
            //'fotografia'             => 'nullable|mimes:png,jpg,jpeg,PNG,JPG,JPEG|max:1000',
            'fotografia'             => 'nullable|mimetypes:image/png,image/jpg,image/jpeg|max:1000',
            'claveCertificado'       => 'nullable|string|max:20',
            'fechaIngresoAsociado'    => 'nullable|date_format:Y-m-d|required_if:formulario,ASOCIADO',
            'rutaCertificadoAsociado' => 'nullable|mimes:pdf,PDF|max:1000',

            'fechaIngresoConductor'   => 'nullable|date_format:Y-m-d|required_if:formulario,CONDUCTOR',
            'tipoConductor'           => 'nullable|string|required_if:formulario,CONDUCTOR',
            'agencia'                 => 'nullable|numeric|required_if:formulario,CONDUCTOR',
            'tipoCategoria'           => 'nullable|string|required_if:formulario,CONDUCTOR',
            'numeroLicencia'          => 'nullable|string|min:4|max:30|required_if:formulario,CONDUCTOR',
            'fechaExpedicionLicencia' => 'nullable|date_format:Y-m-d|required_if:formulario,CONDUCTOR',
            'fechaVencimiento'        => 'nullable|date_format:Y-m-d|required_if:formulario,CONDUCTOR',
            'imagenLicencia' 	      => 'nullable|mimes:jpg,png,jpeg,pdf,JPG,PNG,JPEG,PDF|max:1000',
            'certificadoConductor.*'  => 'nullable|mimes:pdf,PFG|max:1000' 
        ];
    }
}