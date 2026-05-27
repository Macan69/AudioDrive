<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CDN для статики (иконки, аватар)
    |--------------------------------------------------------------------------
    |
    | Фото товаров: loudsound.ru (см. product_photos) + локальный кэш
    | public/images/products/{slug}.jpg после scripts/download-loudsound-images.php
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
    | Реальные фото с loudsound.ru (близкие модели, где точного SKU нет).
    */
    'product_photos' => [
        'pioneer-ts-wx300a' => 'https://www.loudsound.ru/upload/iblock/9d4/13vqp28vla276qekbn3f94awd0doc43c/aktivnyy_sabvufer_avtomobilnyy_korpusnoy_aktivnyy_8_pioneer_ts_wx130ea.jpg',
        'alpine-sws-12d4' => 'https://www.loudsound.ru/upload/iblock/6cd/passivnyy_sabvufer_avtomobilnyy_korpusnoy_passivnyy_12_alpine_swt_12s4.jpg',
        'kenwood-kac-m3004' => 'https://www.loudsound.ru/upload/iblock/249/2_kanalnyy-usilitel-kenwood-kac_ps802ex-ot-magazina-avtozvuka-loudsound.jpg',
        'jbl-gx-a602' => 'https://www.loudsound.ru/upload/iblock/e3f/rl7ac8wneee9ycwy32qxa0xse6q5p9b7/4_kanalnyy-usilitel-jbl-concert-a704-ot-magazina-avtozvuka-loudsound.jpg',
        'hertz-dsk-1653' => 'https://www.loudsound.ru/upload/iblock/dec/nb0dr72s3z8qu08np7i1ukz2eh4gc4ig/2_kh_komponentnaya_avtoakustika_hertz_dsk_165_3_loudsound.jpg',
        'focal-165-as' => 'https://www.loudsound.ru/upload/iblock/ebd/2_kh_komponentnaya_avtoakustika_focal_access_165_as_loudsound.jpg',
        'pioneer-dmh-g225bt' => 'https://www.loudsound.ru/upload/iblock/a09/jfrxa2migzxn4xbczfbg641zdpgstugq/avtomagnitola_2din_pioneer_dmh_g225bt_.jpg',
        'alpine-ilx-w650' => 'https://www.loudsound.ru/upload/iblock/a09/jfrxa2migzxn4xbczfbg641zdpgstugq/avtomagnitola_2din_pioneer_dmh_g225bt_.jpg',
        'kabel-akusticeskii-2x25mm' => 'https://www.loudsound.ru/upload/iblock/030/komplekt_provodov_kicx_akc10atc2_10ga_cca_2_kanala_loudsound.jpg',
        'kondensator-2-farad' => 'https://www.loudsound.ru/upload/iblock/683/0zbw3vob35pzd1v58gd17f4ju37460j1/avtomobilnyy-kondensator-recoil-bb_4-ot-magazina-avtozvuka-loudsound.jpg',
        'morel-maximo-ultra-602' => 'https://www.loudsound.ru/upload/iblock/62f/2_kh_komponentnaya_avtoakustika_morel_maximo_ultra_602_mkii_loudsound.jpg',
        'sound-digital-sd-30001' => 'https://www.loudsound.ru/upload/iblock/8a7/1_kanalnyy-usilitel-_monoblok_-dynamic-state-ca_3000.1d-custom-series-ot-magazina-avtozvuka-loudsound.jpg',
    ],

    'fallback_photos' => [
        'subwoofers' => 'https://www.loudsound.ru/upload/iblock/9d4/13vqp28vla276qekbn3f94awd0doc43c/aktivnyy_sabvufer_avtomobilnyy_korpusnoy_aktivnyy_8_pioneer_ts_wx130ea.jpg',
        'amplifiers' => 'https://www.loudsound.ru/upload/iblock/e3f/rl7ac8wneee9ycwy32qxa0xse6q5p9b7/4_kanalnyy-usilitel-jbl-concert-a704-ot-magazina-avtozvuka-loudsound.jpg',
        'speakers' => 'https://www.loudsound.ru/upload/iblock/dec/nb0dr72s3z8qu08np7i1ukz2eh4gc4ig/2_kh_komponentnaya_avtoakustika_hertz_dsk_165_3_loudsound.jpg',
        'head-units' => 'https://www.loudsound.ru/upload/iblock/a09/jfrxa2migzxn4xbczfbg641zdpgstugq/avtomagnitola_2din_pioneer_dmh_g225bt_.jpg',
        'accessories' => 'https://www.loudsound.ru/upload/iblock/030/komplekt_provodov_kicx_akc10atc2_10ga_cca_2_kanala_loudsound.jpg',
    ],

    'hero_photo' => '/images/hero-audio.jpg',

];
