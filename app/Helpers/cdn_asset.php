<?php

use App\Services\ProductImageCdn;

if (! function_exists('cdn_asset')) {
    function cdn_asset(string $path): string
    {
        return app(ProductImageCdn::class)->staticUrl($path);
    }
}
