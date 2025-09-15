<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdenDeServicioRequest extends FormRequest
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
            'tipo_evaluacion' => 'required',
            'medio_venta' => 'required',
            'enfasis' => 'required',
            'prestador_saluds_id' => 'required',
            'paciente_id' => 'required',
            'tarifasSeleccionadas' => 'required',

        ];
    }
    public function messages(): array
    {
        return [
            'tipo_evaluacion.required' => 'Debe seleccionar un tipo de evaluacion.',
            'medio_venta.required' => 'Debe seleccionar el medio venta.',
            'enfasis.required' => 'Debe seleccionar al menos un tipo.',
            'prestador_saluds_id.required' => 'Es importante seleccionar un prestador salud.',
            'paciente_id.required' => 'Debe seleccionar un paciente.',
            'paciente_id.exists'   => 'El paciente seleccionado no es válido.',
            'tarifasSeleccionadas.required'   => 'Debe seleccionar al menos un examen.',
        ];
    }
}
