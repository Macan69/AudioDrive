<?php

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

(new App\Services\ProductDemoImageService())->generateHero();

$info = @getimagesize(__DIR__.'/../public/images/hero-audio.jpg');
echo $info ? "hero: {$info[0]}x{$info[1]} {$info['mime']}\n" : "hero: failed\n";
