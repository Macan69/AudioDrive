<?php

/**
 * Убирает шахматный/белый/синий фон с hero-сабвуфера.
 * php scripts/remove-hero-background.php [source] [dest]
 */

$source = $argv[1] ?? __DIR__.'/../public/images/hero-audio.png';
$dest = $argv[2] ?? __DIR__.'/../public/images/hero-audio.png';

if (! extension_loaded('gd')) {
    fwrite(STDERR, "GD required\n");
    exit(1);
}

$src = @imagecreatefrompng($source);
if (! $src) {
    fwrite(STDERR, "Cannot load: {$source}\n");
    exit(1);
}

$width = imagesx($src);
$height = imagesy($src);

$out = imagecreatetruecolor($width, $height);
imagealphablending($out, false);
imagesavealpha($out, true);

$transparent = imagecolorallocatealpha($out, 0, 0, 0, 127);
imagefill($out, 0, 0, $transparent);

$mask = array_fill(0, $height, array_fill(0, $width, false));
$queue = new SplQueue;

foreach (edgePixels($width, $height) as [$x, $y]) {
    $rgba = imagecolorat($src, $x, $y);
    $r = ($rgba >> 16) & 0xFF;
    $g = ($rgba >> 8) & 0xFF;
    $b = $rgba & 0xFF;

    if (isBackgroundPixel($r, $g, $b, true)) {
        $queue->enqueue([$x, $y]);
        $mask[$y][$x] = true;
    }
}

while (! $queue->isEmpty()) {
    [$x, $y] = $queue->dequeue();

    foreach (neighbors($x, $y, $width, $height) as [$nx, $ny]) {
        if ($mask[$ny][$nx]) {
            continue;
        }

        $rgba = imagecolorat($src, $nx, $ny);
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        if (! isBackgroundPixel($r, $g, $b, true)) {
            continue;
        }

        $mask[$ny][$nx] = true;
        $queue->enqueue([$nx, $ny]);
    }
}

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        if ($mask[$y][$x]) {
            continue;
        }

        $rgba = imagecolorat($src, $x, $y);
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;

        if (isBackgroundPixel($r, $g, $b, false)) {
            $mask[$y][$x] = true;
        }
    }
}

for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        if ($mask[$y][$x]) {
            continue;
        }

        $rgba = imagecolorat($src, $x, $y);
        $r = ($rgba >> 16) & 0xFF;
        $g = ($rgba >> 8) & 0xFF;
        $b = $rgba & 0xFF;
        $color = imagecolorallocatealpha($out, $r, $g, $b, 0);
        imagesetpixel($out, $x, $y, $color);
    }
}

imagepng($out, $dest, 6);
imagedestroy($src);
imagedestroy($out);

echo "Saved: {$dest} ({$width}x{$height})\n";

function isBackgroundPixel(int $r, int $g, int $b, bool $fromEdge): bool
{
    if (isSpeakerPixel($r, $g, $b)) {
        return false;
    }

    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $chroma = $max - $min;

    if ($max >= 238 && $chroma <= 20) {
        return true;
    }

    if ($chroma <= 14 && $min >= 165 && $max <= 245) {
        return true;
    }

    if ($b > $r + 12 && $b > $g + 8 && $max < 90) {
        return true;
    }

    if (! $fromEdge && $max >= 205 && $chroma <= 18) {
        return true;
    }

    return false;
}

function isSpeakerPixel(int $r, int $g, int $b): bool
{
    $max = max($r, $g, $b);

    if ($r > 120 && $r > $g + 35 && $r > $b + 35) {
        return true;
    }

    if ($max < 95) {
        return true;
    }

    return false;
}

/** @return list<array{0:int,1:int}> */
function edgePixels(int $width, int $height): array
{
    $points = [];
    for ($x = 0; $x < $width; $x++) {
        $points[] = [$x, 0];
        $points[] = [$x, $height - 1];
    }
    for ($y = 1; $y < $height - 1; $y++) {
        $points[] = [0, $y];
        $points[] = [$width - 1, $y];
    }

    return $points;
}

/** @return list<array{0:int,1:int}> */
function neighbors(int $x, int $y, int $width, int $height): array
{
    $list = [];
    foreach ([[$x - 1, $y], [$x + 1, $y], [$x, $y - 1], [$x, $y + 1]] as [$nx, $ny]) {
        if ($nx >= 0 && $nx < $width && $ny >= 0 && $ny < $height) {
            $list[] = [$nx, $ny];
        }
    }

    return $list;
}
