<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if ($this->app->environment('production') && str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }

    public static function siteStylesheet(): string
    {
        return self::minifiedStylesheet('site');
    }

    public static function adminStylesheet(): string
    {
        return self::minifiedStylesheet('admin');
    }

    private static function minifiedStylesheet(string $name): string
    {
        $min = public_path("css/{$name}.min.css");

        if (File::exists($min)) {
            return asset("css/{$name}.min.css");
        }

        return asset("css/{$name}.css");
    }
}
