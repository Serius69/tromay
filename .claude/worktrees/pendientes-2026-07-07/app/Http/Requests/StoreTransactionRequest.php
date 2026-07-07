<?php

namespace App\Http\Requests;

use App\Services\TransactionService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'ci'       => ['required', 'string', 'max:20'],
            'name'     => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'cash1_id' => ['required', 'integer', 'exists:cashes,id'],
            'cash2_id' => ['required', 'integer', 'exists:cashes,id'],
            'amount1'  => ['required', 'numeric', 'min:0.01', 'max:10000000'],
            'date'     => ['required', 'date'],
            'type'     => ['required', 'integer', Rule::in([TransactionService::TYPE_BUY, TransactionService::TYPE_SELL])],
        ];
    }

    public function messages(): array
    {
        return [
            'ci.required'       => 'El CI del cliente es obligatorio.',
            'name.required'     => 'El nombre del cliente es obligatorio.',
            'lastname.required' => 'El apellido del cliente es obligatorio.',
            'cash1_id.required' => 'Debe seleccionar la divisa de origen.',
            'cash1_id.exists'   => 'La divisa de origen no existe.',
            'cash2_id.required' => 'Debe seleccionar la divisa de destino.',
            'cash2_id.exists'   => 'La divisa de destino no existe.',
            'amount1.required'  => 'El monto es obligatorio.',
            'amount1.numeric'   => 'El monto debe ser un número.',
            'amount1.min'       => 'El monto debe ser mayor a cero.',
            'date.required'     => 'La fecha de la transacción es obligatoria.',
            'date.date'         => 'La fecha no tiene un formato válido.',
            'type.required'     => 'El tipo de operación es obligatorio.',
            'type.in'           => 'El tipo debe ser compra (1) o venta (2).',
        ];
    }
}
