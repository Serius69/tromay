<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'id'          => ['nullable', 'integer', 'exists:quotations,id'],
            'client_id'   => ['nullable', 'integer', 'exists:clients,id'],
            'cash_id'     => ['required', 'integer', 'exists:cashes,id'],
            'type'        => ['required', 'string', 'in:buy,sell'],
            'amount'      => ['required', 'numeric', 'min:0.01', 'max:99999999'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:today'],
            'notes'       => ['nullable', 'string', 'max:500'],
            'status'      => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'cash_id.required'      => 'La divisa es obligatoria.',
            'cash_id.exists'        => 'La divisa seleccionada no existe.',
            'type.required'         => 'El tipo de operación es obligatorio.',
            'type.in'               => 'El tipo debe ser compra (buy) o venta (sell).',
            'amount.required'       => 'El monto es obligatorio.',
            'amount.numeric'        => 'El monto debe ser un número.',
            'amount.min'            => 'El monto debe ser mayor a cero.',
            'valid_until.after_or_equal' => 'La fecha de validez no puede ser anterior a hoy.',
            'client_id.exists'      => 'El cliente seleccionado no existe.',
        ];
    }
}
