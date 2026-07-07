<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCashRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:50'],
            'buy'     => ['required', 'numeric', 'min:0', 'max:99999999'],
            'sell'    => ['required', 'numeric', 'min:0', 'max:99999999'],
            'oficial' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'status'  => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'El nombre de la divisa es obligatorio.',
            'name.max'        => 'El nombre no puede superar los 50 caracteres.',
            'buy.required'    => 'La tasa de compra es obligatoria.',
            'buy.numeric'     => 'La tasa de compra debe ser un número.',
            'buy.min'         => 'La tasa de compra no puede ser negativa.',
            'sell.required'   => 'La tasa de venta es obligatoria.',
            'sell.numeric'    => 'La tasa de venta debe ser un número.',
            'sell.min'        => 'La tasa de venta no puede ser negativa.',
            'oficial.numeric' => 'La tasa oficial debe ser un número.',
            'status.required' => 'El estado es obligatorio.',
            'status.in'       => 'El estado debe ser activo (1) o inactivo (0).',
        ];
    }
}
