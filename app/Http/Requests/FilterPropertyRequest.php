<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class FilterPropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'city' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gt:min_price'],
            'status' => ['nullable', 'string'],
            'search' => ['nullable', 'string', 'min:2', 'max:255'],
            'only_published' => ['boolean'],
            'per_page' => ['integer', 'min:1', 'max:100'],
            'page' => ['integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'min_price.numeric' => 'Le prix minimum doit être un nombre.',
            'max_price.numeric' => 'Le prix maximum doit être un nombre.',
            'max_price.gt' => 'Le prix maximum doit être supérieur au prix minimum.',
            'search.min' => 'La recherche doit contenir au moins :min caractères.',
            'per_page.max' => 'Le nombre d\'éléments par page ne peut pas dépasser :max.',
            'page.min' => 'Le numéro de page doit être au moins 1.',
        ];
    }
}
