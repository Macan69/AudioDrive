<?php

/**
 * Скачивает валидные JPG из storage/app/loudsound-images.json
 * php scripts/download-loudsound-images.php
 */

$map = json_decode(file_get_contents(__DIR__.'/../storage/app/loudsound-images.json'), true);
$photos = $map['product_photos'] ?? [];
$outDir = __DIR__.'/../public/images/products';

$ctx = stream_context_create([
    'http' => [
        'timeout' => 90,
        'header' => "User-Agent: Mozilla/5.0\r\nReferer: https://www.loudsound.ru/\r\n",
    ],
]);

$ok = 0;
foreach ($photos as $slug => $url) {
    if (! $url) {
        echo "{$slug}: skip\n";
        continue;
    }

    $dest = $outDir.'/'.$slug.'.jpg';
    $data = @file_get_contents($url, false, $ctx);
    if ($data === false || strlen($data) < 2000) {
        echo "{$slug}: failed\n";
        continue;
    }

    file_put_contents($dest, $data);
    if (! @getimagesize($dest)) {
        @unlink($dest);
        echo "{$slug}: invalid jpeg\n";
        continue;
    }

    [$w, $h] = getimagesize($dest);
    echo "{$slug}: {$w}x{$h}\n";
    $ok++;
}

echo "Saved: {$ok}\n";
