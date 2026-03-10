<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, Relations\BelongsTo};
use Illuminate\Support\Facades\Storage;

final class PropertyImage extends Model
{
    use HasFactory;
    
    protected $table = 'property_images';

    protected $appends = ['url'];
    
    protected $fillable = [
        'property_id',
        'filename',
        'original_name',
        'mime_type',
        'size',
        'path',
    ];

    protected function casts()
    {
        return [
            'size' => 'integer',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function deleteImage(): bool
    {
        if (Storage::exists($this->path)) {
            Storage::delete($this->path);
        }

        return $this->delete();
    }
}
