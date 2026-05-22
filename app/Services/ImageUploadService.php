<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    private const MAX_SIDE = 1200;

    private const JPEG_QUALITY = 82;

    public function storeProductImage(UploadedFile $file): string
    {
        if (! extension_loaded('gd')) {
            return $file->store('products', 'public');
        }

        $filename = Str::random(40).'.jpg';
        $path = 'products/'.$filename;
        $fullPath = Storage::disk('public')->path($path);

        $source = $this->loadImage($file);
        if (! $source) {
            return $file->store('products', 'public');
        }

        $source = $this->resizeIfNeeded($source, self::MAX_SIDE);
        imagejpeg($source, $fullPath, self::JPEG_QUALITY);
        imagedestroy($source);

        return $path;
    }

    public function optimizeExisting(string $relativePath): bool
    {
        if (! extension_loaded('gd') || ! Storage::disk('public')->exists($relativePath)) {
            return false;
        }

        return $this->saveJpeg(Storage::disk('public')->path($relativePath), self::MAX_SIDE, self::JPEG_QUALITY);
    }

    public function optimizePublicFile(string $absolutePath, int $maxSide = 1200, int $quality = 82): int
    {
        if (! extension_loaded('gd') || ! is_file($absolutePath)) {
            return 0;
        }

        $before = filesize($absolutePath) ?: 0;
        $source = @imagecreatefromstring((string) file_get_contents($absolutePath));
        if (! $source) {
            return 0;
        }

        $source = $this->resizeIfNeeded($source, $maxSide);
        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        $saved = $ext === 'png'
            ? imagepng($source, $absolutePath, 8)
            : imagejpeg($source, $absolutePath, $quality);
        imagedestroy($source);

        if (! $saved) {
            return 0;
        }

        clearstatcache(true, $absolutePath);
        $after = filesize($absolutePath) ?: 0;

        return max(0, $before - $after);
    }

    private function saveJpeg(string $fullPath, int $maxSide, int $quality): bool
    {
        $source = @imagecreatefromstring((string) file_get_contents($fullPath));
        if (! $source) {
            return false;
        }

        $source = $this->resizeIfNeeded($source, $maxSide);
        $saved = imagejpeg($source, $fullPath, $quality);
        imagedestroy($source);

        return $saved;
    }

    private function loadImage(UploadedFile $file): ?\GdImage
    {
        $path = $file->getRealPath();

        return match ($file->getMimeType()) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path) ?: null,
            'image/png' => $this->loadPng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? (@imagecreatefromwebp($path) ?: null) : null,
            default => null,
        };
    }

    private function loadPng(string $path): ?\GdImage
    {
        $image = @imagecreatefrompng($path);
        if (! $image) {
            return null;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $canvas = imagecreatetruecolor($width, $height);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));
        imagecopy($canvas, $image, 0, 0, 0, 0, $width, $height);
        imagedestroy($image);

        return $canvas;
    }

    private function resizeIfNeeded(\GdImage $image, int $maxSide = self::MAX_SIDE): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= $maxSide && $height <= $maxSide) {
            return $image;
        }

        $ratio = min($maxSide / $width, $maxSide / $height);
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }
}
