<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHiringRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contract_type_id'  => 'required|exists:contract_types,id',
            'school_id'         => 'required|exists:schools,id',
            'modality'          => ['required', Rule::in(['Modalidad Presencial', 'Modalidad en Linea', 'Modalidad Semi-Presencial'])],
            'message'           => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'contract_type_id.required' => 'El tipo de contrato es requerido',
            'contract_type_id.exists'   => 'El tipo de contrato no existe en los registros',
            'school_id.required'        => 'La escuela o unidad es requerida',
            'school_id.exists'          => 'La escuela o unidad no existe en los registros',
            'modality.required'         => 'El tipo de modalidad es requerido',
            'modality.in'               => 'El tipo de modalidad no es válido, los validos son: Modalidad Presencial y Modalidad en Linea',
            'message.required'          => 'El mensaje de la solicitud es requerido',
            'message.string'            => 'El mensaje debe ser una cadena de caracteres',
        ];
    }
}
