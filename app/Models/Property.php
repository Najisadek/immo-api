<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\{PropertyStatus, PropertyType};
use Illuminate\Database\Eloquent\{
    Model, 
    Relations\BelongsTo, 
    Relations\HasMany, 
    SoftDeletes, 
    Attributes\Scope,
    Factories\HasFactory
};

final class Property extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'rooms',
        'surface',
        'price',
        'city',
        'description',
        'status',
        'is_published',
    ];

    protected $casts = [
        'surface' => 'decimal:2',
        'price' => 'decimal:2',
        'rooms' => 'integer',
        'is_published' => 'boolean',
        'type' => PropertyType::class,
        'status' => PropertyStatus::class,
    ];

    protected static function booted(): void
    {
        self::creating(function ($property) {
            $property->generateTitle();
        });

        self::updating(function ($property) {
            if ($property->isDirty(['type', 'rooms', 'city'])) {
                $property->generateTitle();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    #[Scope]
    public function published($query)
    {
        return $query->where('is_published', true);
    }

    #[Scope]
    public function available($query)
    {
        return $query->where('status', PropertyStatus::Available);
    }

    #[Scope]
    public function byCity($query, string $city)
    {
        return $query->where('city', 'LIKE', "%{$city}%");
    }

    #[Scope]
    public function byType($query, string $type)
    {
        return $query->where('type', $type);
    }

    #[Scope]
    public function byPriceRange($query, ?float $min = null, ?float $max = null)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }

        return $query;
    }

    #[Scope]
    public function byStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    #[Scope]
    public function search($query, string $search)
    {
        return $query->where('title', 'LIKE', "%{$search}%")
            ->orWhere('description', 'LIKE', "%{$search}%");
    }

    public function generateTitle(): void
    {
        $type = ucfirst($this->type?->value ?? $this->type);
        $rooms = $this->rooms ? $this->rooms.' pièces' : '';
        $city = $this->city;

        if ($this->type === PropertyType::Land) {
            $this->title = "{$type} à {$city}";
        } elseif ($this->type === PropertyType::Studio) {
            $this->title = "{$type} à {$city}";
        } else {
            $this->title = "{$type} {$rooms} à {$city}";
        }
    }
}
