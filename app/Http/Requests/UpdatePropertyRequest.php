<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\{PropertyStatus, PropertyType};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $property = $this->route('property');

        return $this->user()?->can('update', $property) ?? false;
    }

    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', Rule::in(PropertyType::values())],
            'rooms' => ['nullable', 'integer', 'min:0', 'max:50'],
            'surface' => ['sometimes', 'numeric', 'min:1', 'max:100000'],
            'price' => ['sometimes', 'numeric', 'min:0', 'max:999999999.99'],
            'city' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string', 'min:10', 'max:5000'],
            'status' => ['sometimes', 'string', Rule::in(PropertyStatus::values())],
            'is_published' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Le type de bien sélectionné n\'est pas valide.',
            'rooms.integer' => 'Le nombre de pièces doit être un nombre entier.',
            'surface.numeric' => 'La surface doit être un nombre.',
            'description.min' => 'La description doit contenir au moins :min caractères.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }
}
