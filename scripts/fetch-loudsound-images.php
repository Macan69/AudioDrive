<?php

/**
 * php scripts/fetch-loudsound-images.php
 */

$pages = [
    'pioneer-ts-wx300a' => 'https://www.loudsound.ru/catalog/aktivnye/pioneer_ts_wx130ea/',
    'alpine-sws-12d4' => 'https://www.loudsound.ru/catalog/korpusnye/alpine_swt_12s4/',
    'kenwood-kac-m3004' => 'https://www.loudsound.ru/catalog/usiliteli_1/kenwood_kac_ps802ex/',
    'jbl-gx-a602' => 'https://www.loudsound.ru/catalog/usiliteli_1/jbl_concert_a704/',
    'hertz-dsk-1653' => 'https://www.loudsound.ru/catalog/2kh_polosnaya/komponentnaya_akustika_hertz_dsk_165_3/',
    'focal-165-as' => 'https://www.loudsound.ru/catalog/2kh_polosnaya/komponentnaya_akustika_focal_access_165_as/',
    'pioneer-dmh-g225bt' => 'https://www.loudsound.ru/catalog/2_din_golovnye_ustroystva/pioneer_dmh_g225bt/',
    'alpine-ilx-w650' => 'https://www.loudsound.ru/catalog/2_din_golovnye_ustroystva/alpine_ilx_f903d/',
    'kabel-akusticeskii-2x25mm' => 'https://www.loudsound.ru/catalog/komplekty_provodov/kicx_akc10atc2/',
    'kondensator-2-farad' => 'https://www.loudsound.ru/catalog/avtomobilnye_kondensatory/recoil_bb_4/',
    'morel-maximo-ultra-602' => 'https://www.loudsound.ru/catalog/2kh_polosnaya/morel_maximo_ultra_602_mkii/',
    'sound-digital-sd-30001' => 'https://www.loudsound.ru/catalog/usiliteli_1/dynamic_state_ca_3000_1d_custom_series/',
];

function httpGet(string $url): ?string
{
    $ctx = stream_context_create([
        'http' => [
            'timeout' => 20,
            'header' => "User-Agent: Mozilla/5.0 (compatible; AudioDrive/1.0)\r\n",
        ],
    ]);

    $html = @file_get_contents($url, false, $ctx);

    return $html !== false ? $html : null;
}

function ogImage(string $html): ?string
{
    if (preg_match('/<meta\s+property="og:image"\s+content="([^"]+)"/i', $html, $m)) {
        return html_entity_decode($m[1]);
    }

    return null;
}

$productPhotos = [];
foreach ($pages as $slug => $url) {
    $html = httpGet($url);
    $image = $html ? ogImage($html) : null;
    $productPhotos[$slug] = $image;
    echo $slug.PHP_EOL.'  '.$url.PHP_EOL.'  '.($image ?: '—').PHP_EOL;
}

$heroHtml = httpGet('https://www.loudsound.ru/');
$hero = $heroHtml ? ogImage($heroHtml) : null;

echo 'hero: '.($hero ?: '—').PHP_EOL;

file_put_contents(__DIR__.'/../storage/app/loudsound-images.json', json_encode([
    'hero' => $hero,
    'product_photos' => $productPhotos,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

echo PHP_EOL.'OK'.PHP_EOL;
