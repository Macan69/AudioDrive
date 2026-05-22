<?php

namespace App\Services;

use App\Models\Product;

class ProductImageCdn
{
    public const KEY_PREFIX = 'cdn:';

    public function isEnabled(): bool
    {
        return (bool) config('shop.cdn.enabled', true);
    }

    public function productUrl(string $slug): string
    {
        $mapped = config("shop.product_photos.{$slug}");
        if ($mapped) {
            return $mapped;
        }

        $relative = 'images/products/'.$slug.'.jpg';
        if ($this->isValidLocalImage(public_path($relative))) {
            return asset($relative);
        }

        if ($this->isEnabled()) {
            return config('shop.cdn.products_base').'/'.rawurlencode($slug).'.jpg';
        }

        return asset($relative);
    }

    public function staticUrl(string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');

        if ($this->isEnabled()) {
            return config('shop.cdn.static_base').'/'.$path;
        }

        return asset($path);
    }

    public function keyForSlug(string $slug): string
    {
        return self::KEY_PREFIX.$slug;
    }

    public function slugFromKey(?string $value): ?string
    {
        if (! $value || ! str_starts_with($value, self::KEY_PREFIX)) {
            return null;
        }

        return substr($value, strlen(self::KEY_PREFIX));
    }

    public function resolve(Product $product): string
    {
        $path = $product->image;

        if ($path && (str_starts_with($path, 'http://') || str_starts_with($path, 'https://'))) {
            return $path;
        }

        $slug = $this->slugFromKey($path) ?? $product->slug;

        if ($path && str_starts_with($path, self::KEY_PREFIX)) {
            return $this->productUrl($slug);
        }

        if ($path && str_starts_with($path, 'images/products/')) {
            $slug = pathinfo($path, PATHINFO_FILENAME);

            return $this->productUrl($slug);
        }

        return $this->fallbackFor($product);
    }

    public function fallbackFor(Product $product): string
    {
        $product->loadMissing('category');
        $categorySlug = $product->category?->slug ?? 'accessories';

        $fallback = config("shop.fallback_photos.{$categorySlug}")
            ?? config('shop.fallback_photos.accessories');

        if ($fallback) {
            return str_starts_with($fallback, 'http') ? $fallback : asset(ltrim($fallback, '/'));
        }

        return asset('images/product-placeholder.svg');
    }

    private function isValidLocalImage(string $absolutePath): bool
    {
        return is_file($absolutePath) && @getimagesize($absolutePath) !== false;
    }
}
