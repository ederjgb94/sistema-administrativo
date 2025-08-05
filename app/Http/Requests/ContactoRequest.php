<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Por ahora solo hay administradores
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $contactoId = $this->route('contacto')?->id;

        return [
            'tipo' => ['required', 'string', Rule::in(['cliente', 'proveedor', 'ambos'])],
            'nombre' => ['required', 'string', 'min:2', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('contactos', 'email')->ignore($contactoId)->whereNull('deleted_at')
            ],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[\d\s\+\-\(\)]+$/'],
            'direccion' => ['nullable', 'string', 'max:500'],
            'rfc' => [
                'nullable',
                'string',
                'max:13',
                'regex:/^[A-ZÑ&]{3,4}\d{6}[A-Z\d]{3}$/',
                Rule::unique('contactos', 'rfc')->ignore($contactoId)->whereNull('deleted_at')
            ],
            'activo' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'tipo.required' => 'El tipo de contacto es obligatorio.',
            'tipo.in' => 'El tipo debe ser: cliente, proveedor o ambos.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre no puede exceder 255 caracteres.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'telefono.regex' => 'El teléfono solo puede contener números, espacios, guiones, paréntesis y el signo +.',
            'direccion.max' => 'La dirección no puede exceder 500 caracteres.',
            'rfc.regex' => 'El RFC debe tener el formato correcto (ej: XAXX010101000).',
            'rfc.unique' => 'Este RFC ya está registrado.',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'tipo' => 'tipo de contacto',
            'nombre' => 'nombre',
            'email' => 'correo electrónico',
            'telefono' => 'teléfono',
            'direccion' => 'dirección',
            'rfc' => 'RFC',
            'activo' => 'estado activo',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Limpiar el teléfono de espacios extra
        if ($this->has('telefono')) {
            $this->merge([
                'telefono' => preg_replace('/\s+/', ' ', trim($this->telefono))
            ]);
        }

        // Convertir RFC a mayúsculas
        if ($this->has('rfc') && !empty($this->rfc)) {
            $this->merge([
                'rfc' => strtoupper(trim($this->rfc))
            ]);
        }

        // Asegurar que activo sea boolean
        if (!$this->has('activo')) {
            $this->merge(['activo' => true]);
        }
    }
}
