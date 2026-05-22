<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CDN для статики (иконки, аватар)
    |--------------------------------------------------------------------------
    |
    | Фото товаров — локальные файлы public/images/products/{slug}.jpg
    | (генерация: php artisan products:generate-images).
    |
    */

    'cdn' => [
        'enabled' => (bool) env('CDN_ENABLED', true),

        'static_base' => rtrim(env(
            'CDN_STATIC_BASE',
            'https://cdn.jsdelivr.net/gh/Macan69/AudioDrive@main/public'
        ), '/'),

        'products_base' => rtrim(env(
            'CDN_PRODUCTS_BASE',
            'https://cdn.jsdelivr.net/gh/Macan69/AudioDrive@main/public/images/products'
        ), '/'),
    ],

    'product_photos' => [],

    'fallback_photos' => [
        'subwoofers' => '/images/products/pioneer-ts-wx300a.jpg',
        'amplifiers' => '/images/products/kenwood-kac-m3004.jpg',
        'speakers' => '/images/products/hertz-dsk-1653.jpg',
        'head-units' => '/images/products/pioneer-dmh-g225bt.jpg',
        'accessories' => '/images/products/kabel-akusticeskii-2x25mm.jpg',
    ],

    'hero_photo' => '/images/hero-audio.jpg',

];
