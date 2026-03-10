<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class PropertyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type?->value ?? $this->type,
            'rooms' => $this->rooms,
            'surface' => $this->surface,
            'price' => $this->price,
            'city' => $this->city,
            'description' => $this->description,
            'status' => $this->status?->value ?? $this->status,
            'is_published' => $this->is_published,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user' => $this->when($this->user, function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                ];
            }),
            'images' => PropertyImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
