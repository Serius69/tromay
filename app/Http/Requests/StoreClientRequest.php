<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $clientId = $this->input('client_id');

        return [
            'ci'       => ['required', 'string', 'max:20', "unique:clients,ci,{$clientId}"],
            'name'     => ['required', 'string', 'max:100'],
            'lastname' => ['required', 'string', 'max:100'],
            'status'   => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'ci.required'       => 'El número de CI es obligatorio.',
            'ci.unique'         => 'Ya existe un cliente con ese número de CI.',
            'ci.max'            => 'El CI no puede superar los 20 caracteres.',
            'name.required'     => 'El nombre es obligatorio.',
            'name.max'          => 'El nombre no puede superar los 100 caracteres.',
            'lastname.required' => 'El apellido es obligatorio.',
            'lastname.max'      => 'El apellido no puede superar los 100 caracteres.',
            'status.required'   => 'El estado es obligatorio.',
            'status.in'         => 'El estado debe ser activo (1) o inactivo (0).',
        ];
    }
}
