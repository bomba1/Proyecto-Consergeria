<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VisitaStoreRequest extends FormRequest
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
            'fecha' => 'required|date|before_or_equal:tomorrow',
            'parentesco' => 'required|regex:/^[aA-zZ]{2,20}$/',
            'empresa_reparto' => 'required|in:SI,NO',
            'persona_id' => 'required|exists:App\Persona,id',
            'propiedad_id' => 'required|exists:App\Propiedad,id',
        ];
    }

    /**
     * Get the messages to send when a validation error occur.
     *
     * @return array|string[]
     */
    public function messages()
    {
        return [
            'fecha.required' => 'Se necesita una fecha',
            'fecha.date' => 'Fecha en formato no valido',
            'fecha.before_or_equal' => 'Fecha no valida',
            'parentesco.required' => 'se requiere parentesco',
            'parentesco.regex' => 'Parentesco debe ser entre 2 y 20 caracteres',
            'empresa_reparto.required' => 'Se requiere saber si es una empresa de reparto',
            'empresa_reparto.in' => 'Debe ser SI o NO',
            'persona_id.required' => 'Se requiere la persona',
            'persona_id.exists' => 'La persona no existe',
            'propiedad_id.required' => 'Se requiere la propiedad',
            'propiedad_id.exists' => 'La propiedad no existe',
        ];
    }

    /**
     * FailedValidation [Overriding the event validator for custom error response].
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation Error',
                'error' => $validator->errors()->all()
            ])
        );
    }
}
