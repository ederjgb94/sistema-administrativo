<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransaccionRequest extends FormRequest
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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $transaccionId = $this->route('transaccione'); // Laravel auto-singulariza rutas resource

        return [
            // Datos básicos
            'folio' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('transacciones', 'folio')->ignore($transaccionId),
            ],
            'tipo' => ['required', 'in:ingreso,egreso'],
            'fecha' => ['required', 'date', 'before_or_equal:today'],
            'contacto_id' => ['nullable', 'exists:contactos,id'],

            // Referencia (obra/producto/servicio/otro)
            'referencia_tipo' => ['required', 'in:obra,producto,servicio,otro'],
            'referencia_nombre' => ['required', 'string', 'max:255'],
            'referencia_datos' => ['nullable', 'array'],
            'referencia_datos.descripcion' => ['nullable', 'string'],
            'referencia_datos.ubicacion' => ['nullable', 'string'],
            'referencia_datos.especificaciones' => ['nullable', 'array'],

            // Facturación
            'factura_tipo' => ['required', 'in:manual,archivo'],
            'factura_numero' => ['nullable', 'string', 'max:100'],
            'factura_datos' => ['nullable', 'array'],
            'factura_datos.emisor' => ['nullable', 'string'],
            'factura_datos.receptor' => ['nullable', 'string'],
            'factura_datos.fecha_emision' => ['nullable', 'date'],
            'factura_datos.conceptos' => ['nullable', 'array'],
            'factura_datos.conceptos.*.descripcion' => ['nullable', 'string'],
            'factura_datos.conceptos.*.cantidad' => ['nullable', 'numeric', 'min:0'],
            'factura_datos.conceptos.*.precio' => ['nullable', 'numeric', 'min:0'],
            'factura_datos.conceptos.*.subtotal' => ['nullable', 'numeric', 'min:0'],
            'factura_datos.subtotal' => ['nullable', 'numeric', 'min:0'],
            'factura_datos.impuestos' => ['nullable', 'numeric', 'min:0'],
            'factura_datos.total' => ['nullable', 'numeric', 'min:0'],
            'factura_datos.condiciones' => ['nullable', 'string'],
            'factura_datos.notas' => ['nullable', 'string'],

            // Archivos de factura
            'factura_archivos.*' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
                'max:10240', // 10MB
            ],

            // Datos financieros
            'metodo_pago_id' => ['required', 'exists:metodos_pago,id'],
            'referencia_pago' => ['nullable', 'string', 'max:100'],
            'total' => ['required', 'numeric', 'min:0.01', 'max:999999999.9999'],

            // Observaciones
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'folio' => 'folio',
            'tipo' => 'tipo de transacción',
            'fecha' => 'fecha',
            'contacto_id' => 'contacto',
            'referencia_tipo' => 'tipo de referencia',
            'referencia_nombre' => 'nombre de referencia',
            'referencia_datos' => 'datos de referencia',
            'factura_tipo' => 'tipo de factura',
            'factura_numero' => 'número de factura',
            'factura_datos' => 'datos de factura',
            'factura_archivos.*' => 'archivo de factura',
            'metodo_pago_id' => 'método de pago',
            'referencia_pago' => 'referencia de pago',
            'total' => 'total',
            'observaciones' => 'observaciones',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'folio.unique' => 'El folio ya existe en el sistema.',
            'fecha.before_or_equal' => 'La fecha no puede ser mayor a hoy.',
            'total.min' => 'El total debe ser mayor a 0.',
            'factura_archivos.*.mimes' => 'Solo se permiten archivos JPG, PNG, PDF, DOC y DOCX.',
            'factura_archivos.*.max' => 'Cada archivo no puede superar los 10MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Generar folio automáticamente si no se proporciona
        if (!$this->has('folio') || empty($this->folio)) {
            $prefijo = strtoupper(substr($this->tipo ?? 'TXN', 0, 3));
            $numero = str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
            $this->merge([
                'folio' => $prefijo . '-' . date('Y') . '-' . $numero
            ]);
        }

        // Limpiar el total
        if ($this->has('total')) {
            $this->merge([
                'total' => (float) str_replace(',', '', $this->total)
            ]);
        }
    }
}
