<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CDN для статики и фото товаров
    |--------------------------------------------------------------------------
    |
    | jsDelivr раздаёт файлы из GitHub-репозитория. Товары в БД хранят ключ
    | вида cdn:{slug} — полный URL собирается в ProductImageCdn.
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

    /*
    | Фото товаров (Unsplash CDN) — стабильные URL с параметрами размера.
    */
    'product_photos' => [
        'pioneer-ts-wx300a' => 'https://images.unsplash.com/photo-1598488035139-bdbb5a590840?w=800&h=600&fit=crop&q=80',
        'alpine-sws-12d4' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop&q=80',
        'kenwood-kac-m3004' => 'https://images.unsplash.com/photo-1487180144351-b8472da7d491?w=800&h=600&fit=crop&q=80',
        'jbl-gx-a602' => 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=800&h=600&fit=crop&q=80',
        'hertz-dsk-1653' => 'https://images.unsplash.com/photo-1545454675-3531b543be6d?w=800&h=600&fit=crop&q=80',
        'focal-165-as' => 'https://images.unsplash.com/photo-1478737273-99004f03f5b8?w=800&h=600&fit=crop&q=80',
        'pioneer-dmh-g225bt' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&h=600&fit=crop&q=80',
        'alpine-ilx-w650' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&h=600&fit=crop&q=80',
        'kabel-akusticeskii-2x25mm' => 'https://images.unsplash.com/photo-1486262715619-67b85ebc4ece?w=800&h=600&fit=crop&q=80',
        'kondensator-2-farad' => 'https://images.unsplash.com/photo-1621361365429-988f21e74d1d?w=800&h=600&fit=crop&q=80',
        'morel-maximo-ultra-602' => 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=800&h=600&fit=crop&q=80',
        'sound-digital-sd-30001' => 'https://images.unsplash.com/photo-1617814076367-bae69a26637b?w=800&h=600&fit=crop&q=80',
    ],

    'fallback_photos' => [
        'subwoofers' => 'https://images.unsplash.com/photo-1598488035139-bdbb5a590840?w=800&h=600&fit=crop&q=80',
        'amplifiers' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&h=600&fit=crop&q=80',
        'speakers' => 'https://images.unsplash.com/photo-1545454675-3531b543be6d?w=800&h=600&fit=crop&q=80',
        'head-units' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=800&h=600&fit=crop&q=80',
        'accessories' => 'https://images.unsplash.com/photo-1486262715619-67b85ebc4ece?w=800&h=600&fit=crop&q=80',
    ],

    'hero_photo' => 'https://images.unsplash.com/photo-1598488035139-bdbb5a590840?w=1120&h=840&fit=crop&q=85',

];
