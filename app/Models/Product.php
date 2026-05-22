<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'brand', 'description', 'full_description',
        'features', 'price', 'old_price', 'stock', 'warranty_months', 'country', 'weight',
        'dimensions', 'package_contents', 'installation', 'image', 'is_active', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'old_price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'features' => 'array',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        $path = $this->image;

        if (! $path) {
            return asset('images/product-placeholder.svg');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        if (str_starts_with($path, 'images/') && is_file(public_path($path))) {
            return asset($path);
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/'.$path);
        }

        return asset('images/product-placeholder.svg');
    }

    public function hasImage(): bool
    {
        if (! $this->image) {
            return false;
        }

        $path = ltrim(str_replace('\\', '/', $this->image), '/');

        if (str_starts_with($path, 'images/')) {
            return is_file(public_path($path));
        }

        return Storage::disk('public')->exists($path);
    }

    public function hasDiscount(): bool
    {
        return $this->old_price && $this->old_price > $this->price;
    }
}
