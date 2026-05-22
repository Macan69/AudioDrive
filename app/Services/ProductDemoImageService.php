<?php

namespace App\Services;

class ProductDemoImageService
{
    /** @var array<string, array{bg: int[], accent: int[], label: string}> */
    private const CATEGORY_STYLES = [
        'subwoofers' => ['bg' => [24, 32, 54], 'accent' => [255, 107, 74], 'label' => 'Сабвуфер'],
        'amplifiers' => ['bg' => [18, 42, 58], 'accent' => [56, 189, 248], 'label' => 'Усилитель'],
        'speakers' => ['bg' => [36, 28, 52], 'accent' => [167, 139, 250], 'label' => 'Акустика'],
        'head-units' => ['bg' => [28, 36, 48], 'accent' => [52, 211, 153], 'label' => 'Магнитола'],
        'accessories' => ['bg' => [32, 32, 40], 'accent' => [250, 204, 21], 'label' => 'Аксессуар'],
    ];

    public function generate(string $slug, string $name, string $brand, string $categorySlug): string
    {
        if (! extension_loaded('gd')) {
            throw new \RuntimeException('PHP GD extension is required to generate product images.');
        }

        $style = self::CATEGORY_STYLES[$categorySlug] ?? self::CATEGORY_STYLES['accessories'];
        $width = 800;
        $height = 600;

        $image = imagecreatetruecolor($width, $height);
        [$br, $bg, $bb] = $style['bg'];
        [$ar, $ag, $ab] = $style['accent'];
        $background = imagecolorallocate($image, $br, $bg, $bb);
        $accent = imagecolorallocate($image, $ar, $ag, $ab);
        $white = imagecolorallocate($image, 248, 250, 252);
        $muted = imagecolorallocate($image, 148, 163, 184);
        imagefill($image, 0, 0, $background);

        imagefilledrectangle($image, 0, 0, $width, 8, $accent);
        imagefilledellipse($image, 680, 120, 280, 280, imagecolorallocatealpha($image, $ar, $ag, $ab, 90));
        imagefilledellipse($image, 120, 480, 220, 220, imagecolorallocatealpha($image, 255, 255, 255, 110));

        $font = $this->resolveFont();
        if ($font) {
            imagettftext($image, 14, 0, 40, 52, $accent, $font, mb_strtoupper($style['label']));
            imagettftext($image, 36, 0, 40, 120, $white, $font, $this->fitText($brand, $font, 36, 520));
            $lines = $this->wrapText($name, $font, 22, 680);
            $y = 200;
            foreach ($lines as $line) {
                imagettftext($image, 22, 0, 40, $y, $muted, $font, $line);
                $y += 34;
            }
            imagettftext($image, 12, 0, 40, $height - 36, $muted, $font, $slug);
        } else {
            imagestring($image, 5, 40, 30, $style['label'], $accent);
            imagestring($image, 5, 40, 80, $this->toAscii($brand), $white);
            imagestring($image, 4, 40, 130, $this->toAscii($name), $muted);
            imagestring($image, 3, 40, $height - 28, $slug, $muted);
        }

        $dir = public_path('images/products');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $relative = 'images/products/'.$slug.'.jpg';
        $absolute = public_path($relative);
        imagejpeg($image, $absolute, 85);
        imagedestroy($image);

        return $relative;
    }

    public function generateHero(): void
    {
        if (! extension_loaded('gd')) {
            return;
        }

        $width = 1120;
        $height = 840;
        $image = imagecreatetruecolor($width, $height);
        $bg = imagecolorallocate($image, 15, 23, 42);
        $accent = imagecolorallocate($image, 255, 107, 74);
        $white = imagecolorallocate($image, 248, 250, 252);
        $muted = imagecolorallocate($image, 148, 163, 184);
        imagefill($image, 0, 0, $bg);
        imagefilledellipse($image, 900, 200, 500, 500, imagecolorallocatealpha($image, 255, 107, 74, 100));
        imagefilledellipse($image, 200, 650, 400, 400, imagecolorallocatealpha($image, 56, 189, 248, 110));

        $font = $this->resolveFont();
        if ($font) {
            imagettftext($image, 48, 0, 56, 120, $white, $font, 'AudioDrive');
            imagettftext($image, 24, 0, 56, 180, $muted, $font, 'Сабвуферы · Усилители · Акустика');
            imagettftext($image, 20, 0, 56, 240, $accent, $font, 'Pioneer · Alpine · JBL · Focal');
        } else {
            imagestring($image, 5, 56, 80, 'AudioDrive', $white);
            imagestring($image, 4, 56, 140, 'Car Audio Store', $muted);
        }

        imagejpeg($image, public_path('images/hero-audio.jpg'), 85);
        imagedestroy($image);
    }

    private function resolveFont(): ?string
    {
        $candidates = [
            resource_path('fonts/DejaVuSans.ttf'),
            resource_path('fonts/DejaVuSans-Bold.ttf'),
            'C:\\Windows\\Fonts\\arial.ttf',
            'C:\\Windows\\Fonts\\segoeui.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/TTF/DejaVuSans.ttf',
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    /** @return list<string> */
    private function wrapText(string $text, string $font, int $size, int $maxWidth): array
    {
        $words = preg_split('/\s+/u', trim($text)) ?: [];
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $trial = $current === '' ? $word : $current.' '.$word;
            $box = imagettfbbox($size, 0, $font, $trial);
            $width = abs($box[2] - $box[0]);
            if ($width > $maxWidth && $current !== '') {
                $lines[] = $current;
                $current = $word;
            } else {
                $current = $trial;
            }
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        return array_slice($lines, 0, 3);
    }

    private function fitText(string $text, string $font, int $size, int $maxWidth): string
    {
        $box = imagettfbbox($size, 0, $font, $text);
        if (abs($box[2] - $box[0]) <= $maxWidth) {
            return $text;
        }

        while (mb_strlen($text) > 3) {
            $text = mb_substr($text, 0, -1);
            $box = imagettfbbox($size, 0, $font, $text.'…');
            if (abs($box[2] - $box[0]) <= $maxWidth) {
                return $text.'…';
            }
        }

        return $text;
    }

    private function toAscii(string $text): string
    {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);

        return $converted !== false && $converted !== '' ? $converted : $text;
    }
}
