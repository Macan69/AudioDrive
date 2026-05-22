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

        $source = $this->resizeIfNeeded($source);
        imagejpeg($source, $fullPath, self::JPEG_QUALITY);
        imagedestroy($source);

        return $path;
    }

    public function optimizeExisting(string $relativePath): bool
    {
        if (! extension_loaded('gd') || ! Storage::disk('public')->exists($relativePath)) {
            return false;
        }

        $fullPath = Storage::disk('public')->path($relativePath);
        $source = @imagecreatefromstring(file_get_contents($fullPath));
        if (! $source) {
            return false;
        }

        $source = $this->resizeIfNeeded($source);
        $saved = imagejpeg($source, $fullPath, self::JPEG_QUALITY);
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

    private function resizeIfNeeded(\GdImage $image): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width <= self::MAX_SIDE && $height <= self::MAX_SIDE) {
            return $image;
        }

        $ratio = min(self::MAX_SIDE / $width, self::MAX_SIDE / $height);
        $newWidth = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }
}
