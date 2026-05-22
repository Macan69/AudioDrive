<?php

namespace App\Services;

use App\Models\Product;

class ProductDemoImageService
{
    private const WIDTH = 800;

    private const HEIGHT = 600;

    /** @var array<string, array{int, int, int, int, int, int}> */
    private const CATEGORY_PALETTES = [
        'subwoofers' => [18, 24, 38, 196, 30, 58],
        'amplifiers' => [15, 32, 55, 37, 99, 235],
        'speakers' => [32, 18, 48, 124, 58, 237],
        'head-units' => [22, 28, 42, 14, 165, 160],
        'accessories' => [38, 28, 18, 234, 88, 12],
    ];

    public function ensureForProduct(Product $product): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $product->loadMissing('category');
        $categorySlug = $product->category?->slug ?? 'accessories';
        $relative = 'images/products/'.$product->slug.'.jpg';
        $absolute = public_path($relative);

        if (is_file($absolute)) {
            return $relative;
        }

        $dir = dirname($absolute);
        if (! is_dir($dir) && ! mkdir($dir, 0755, true) && ! is_dir($dir)) {
            return null;
        }

        $palette = self::CATEGORY_PALETTES[$categorySlug] ?? self::CATEGORY_PALETTES['accessories'];
        $image = imagecreatetruecolor(self::WIDTH, self::HEIGHT);
        if (! $image) {
            return null;
        }

        $this->fillGradient($image, $palette);
        $this->drawCategoryGlyph($image, $categorySlug);
        $this->drawLabel($image, $product->brand, $product->name);

        $saved = imagejpeg($image, $absolute, 85);
        imagedestroy($image);

        return $saved ? $relative : null;
    }

    /**
     * @param  array{int, int, int, int, int, int}  $palette
     */
    private function fillGradient(\GdImage $image, array $palette): void
    {
        [$r1, $g1, $b1, $r2, $g2, $b2] = $palette;

        for ($y = 0; $y < self::HEIGHT; $y++) {
            $ratio = $y / self::HEIGHT;
            $r = (int) ($r1 + ($r2 - $r1) * $ratio);
            $g = (int) ($g1 + ($g2 - $g1) * $ratio);
            $b = (int) ($b1 + ($b2 - $b1) * $ratio);
            $color = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, self::WIDTH, $y, $color);
        }

        $panel = imagecolorallocatealpha($image, 255, 255, 255, 90);
        imagefilledrectangle($image, 48, 48, self::WIDTH - 48, self::HEIGHT - 120, $panel);
    }

    private function drawCategoryGlyph(\GdImage $image, string $categorySlug): void
    {
        $cx = (int) (self::WIDTH / 2);
        $cy = (int) (self::HEIGHT / 2) - 20;
        $accent = imagecolorallocate($image, 30, 30, 40);
        $light = imagecolorallocate($image, 90, 95, 110);

        match ($categorySlug) {
            'subwoofers' => $this->drawSubwoofer($image, $cx, $cy, $accent, $light),
            'amplifiers' => $this->drawAmplifier($image, $cx, $cy, $accent, $light),
            'speakers' => $this->drawSpeaker($image, $cx, $cy, $accent, $light),
            'head-units' => $this->drawHeadUnit($image, $cx, $cy, $accent, $light),
            default => $this->drawAccessory($image, $cx, $cy, $accent, $light),
        };
    }

    private function drawSubwoofer(\GdImage $image, int $cx, int $cy, int $dark, int $light): void
    {
        imagefilledellipse($image, $cx, $cy, 220, 220, $dark);
        imagefilledellipse($image, $cx, $cy, 150, 150, $light);
        imagefilledellipse($image, $cx, $cy, 70, 70, $dark);
    }

    private function drawAmplifier(\GdImage $image, int $cx, int $cy, int $dark, int $light): void
    {
        imagefilledrectangle($image, $cx - 140, $cy - 70, $cx + 140, $cy + 70, $dark);
        imagefilledrectangle($image, $cx - 120, $cy - 50, $cx + 120, $cy + 50, $light);
        for ($i = 0; $i < 4; $i++) {
            imagefilledellipse($image, $cx - 75 + ($i * 50), $cy + 55, 16, 16, $dark);
        }
    }

    private function drawSpeaker(\GdImage $image, int $cx, int $cy, int $dark, int $light): void
    {
        imagefilledellipse($image, $cx, $cy + 20, 180, 180, $dark);
        imagefilledellipse($image, $cx, $cy - 60, 70, 70, $light);
        imagefilledellipse($image, $cx, $cy + 30, 90, 90, $light);
    }

    private function drawHeadUnit(\GdImage $image, int $cx, int $cy, int $dark, int $light): void
    {
        imagefilledrectangle($image, $cx - 150, $cy - 60, $cx + 150, $cy + 60, $dark);
        imagefilledrectangle($image, $cx - 130, $cy - 40, $cx + 130, $cy + 40, $light);
        imagefilledrectangle($image, $cx - 90, $cy - 20, $cx + 90, $cy + 20, $dark);
    }

    private function drawAccessory(\GdImage $image, int $cx, int $cy, int $dark, int $light): void
    {
        imagefilledrectangle($image, $cx - 120, $cy - 15, $cx + 120, $cy + 15, $dark);
        imagefilledellipse($image, $cx - 120, $cy, 30, 30, $light);
        imagefilledellipse($image, $cx + 120, $cy, 30, 30, $light);
    }

    private function drawLabel(\GdImage $image, ?string $brand, string $name): void
    {
        $white = imagecolorallocate($image, 255, 255, 255);
        $muted = imagecolorallocate($image, 220, 224, 230);
        $brandText = strtoupper((string) $brand);
        $title = $this->truncate($name, 34);

        imagestring($image, 5, 64, self::HEIGHT - 96, $brandText, $white);
        imagestring($image, 4, 64, self::HEIGHT - 68, $title, $muted);
    }

    private function truncate(string $text, int $max): string
    {
        if (strlen($text) <= $max) {
            return $text;
        }

        return substr($text, 0, $max - 1).'…';
    }
}
