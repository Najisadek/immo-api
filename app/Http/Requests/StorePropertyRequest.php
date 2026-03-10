<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\{PropertyStatus, PropertyType};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Property;

final class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Property::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', Rule::in(PropertyType::values())],
            'rooms' => ['nullable', 'integer', 'min:0', 'max:50'],
            'surface' => ['required', 'numeric', 'min:1', 'max:100000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'city' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'status' => ['required', 'string', Rule::in(PropertyStatus::values())],
            'is_published' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Le type de bien est obligatoire.',
            'type.in' => 'Le type de bien sélectionné n\'est pas valide.',
            'rooms.integer' => 'Le nombre de pièces doit être un nombre entier.',
            'surface.required' => 'La surface est obligatoire.',
            'surface.numeric' => 'La surface doit être un nombre.',
            'price.required' => 'Le prix est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit contenir au moins :min caractères.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }
}
