<?php

/**
 * Обновляет storage/app/loudsound-images.json (og:image с карточек каталога)
 * php scripts/fetch-loudsound-images.php
 */

$pages = [
    'pioneer-ts-wx300a' => 'https://www.loudsound.ru/catalog/aktivnye/pioneer_ts_wx130ea/',
    'alpine-sws-12d4' => 'https://www.loudsound.ru/catalog/passivnye/alpine_sws_12d4/',
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

$fallbackPages = [
    'alpine-sws-12d4' => 'https://www.loudsound.ru/catalog/korpusnye/alpine_swt_12s4/',
    'alpine-ilx-w650' => 'https://www.loudsound.ru/catalog/2_din_golovnye_ustroystva/pioneer_dmh_g225bt/',
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
    if (! preg_match_all('/<meta\s+property="og:image"\s+content="([^"]+)"/i', $html, $all)) {
        return null;
    }

    foreach ($all[1] as $url) {
        $url = html_entity_decode($url);
        if (str_contains($url, '/upload/iblock/') && ! str_contains($url, 'resize_cache')) {
            return $url;
        }
    }

    return null;
}

$productPhotos = [];

foreach ($pages as $slug => $url) {
    $html = httpGet($url);
    $image = $html ? ogImage($html) : null;

    if (! $image && isset($fallbackPages[$slug])) {
        $html = httpGet($fallbackPages[$slug]);
        $image = $html ? ogImage($html) : null;
    }

    $productPhotos[$slug] = $image;
    echo $slug.': '.($image ?: '—').PHP_EOL;
}

$heroHtml = httpGet('https://www.loudsound.ru/');
$hero = $heroHtml ? ogImage($heroHtml) : 'https://www.loudsound.ru/images/13599f490_rec.png';

file_put_contents(__DIR__.'/../storage/app/loudsound-images.json', json_encode([
    'hero' => $hero,
    'product_photos' => $productPhotos,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

echo 'OK'.PHP_EOL;
