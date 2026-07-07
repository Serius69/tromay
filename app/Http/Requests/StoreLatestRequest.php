<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLatestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:200'],
            'category'         => ['nullable', 'string', 'max:50'],
            'author'           => ['required', 'string', 'max:100'],
            'description'      => ['required', 'string', 'max:5000'],
            'date_publication' => ['required', 'date'],
            'url'              => ['nullable', 'url', 'max:500'],
            'path'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status'           => ['nullable', 'integer', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'             => 'El título de la noticia es obligatorio.',
            'name.max'                  => 'El título no puede superar los 200 caracteres.',
            'author.required'           => 'El autor es obligatorio.',
            'author.max'                => 'El autor no puede superar los 100 caracteres.',
            'description.required'      => 'La descripción es obligatoria.',
            'description.max'           => 'La descripción no puede superar los 5.000 caracteres.',
            'date_publication.required' => 'La fecha de publicación es obligatoria.',
            'date_publication.date'     => 'La fecha de publicación no tiene un formato válido.',
            'url.url'                   => 'La URL no tiene un formato válido.',
            'path.image'                => 'El archivo debe ser una imagen.',
            'path.mimes'                => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'path.max'                  => 'La imagen no puede superar los 2 MB.',
        ];
    }
}
