<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $property = $this->route('property');

        return $this->user()?->can('update', $property) ?? false;
    }

    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Une image est requise.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Les formats acceptés sont : jpeg, png, jpg, webp.',
            'image.max' => 'L\'image ne doit pas dépasser 5 Mo.',
        ];
    }
}
