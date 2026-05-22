<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ImageUploadService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OptimizeProjectCommand extends Command
{
    protected $signature = 'app:optimize-project
        {--images : Сжать фото товаров}
        {--static : Сжать статические изображения в public/images}
        {--css : Минифицировать CSS (site + admin)}';

    protected $description = 'Очистка кэша, оптимизация Laravel, CSS и изображений';

    public function handle(ImageUploadService $images): int
    {
        $this->info('Очистка временных файлов...');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        $log = storage_path('logs/laravel.log');
        if (File::exists($log)) {
            File::put($log, '');
        }

        $this->info('Кэширование конфигурации...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        if ($this->option('css')) {
            $this->minifyCss('site');
            $this->minifyCss('admin');
        }

        if ($this->option('static')) {
            $this->optimizeStaticImages($images);
        }

        if ($this->option('images')) {
            if (! extension_loaded('gd')) {
                $this->warn('Расширение GD не включено — сжатие изображений пропущено.');
            } else {
                $products = Product::whereNotNull('image')->get();
                $this->info('Сжатие фото товаров...');
                $bar = $this->output->createProgressBar($products->count());
                $savedBytes = 0;

                foreach ($products as $product) {
                    $full = Storage::disk('public')->path($product->image);
                    $before = file_exists($full) ? filesize($full) : 0;

                    if ($images->optimizeExisting($product->image) && $before > 0) {
                        clearstatcache(true, $full);
                        $after = filesize($full);
                        $savedBytes += max(0, $before - $after);
                    }
                    $bar->advance();
                }

                $bar->finish();
                $this->newLine();
                $this->info('Товары: сэкономлено '.round($savedBytes / 1024).' КБ');
            }
        }

        $this->newLine();
        $this->comment('Продакшен: composer prod-install && composer optimize-all');

        return self::SUCCESS;
    }

    private function minifyCss(string $name): void
    {
        $source = public_path("css/{$name}.css");
        $target = public_path("css/{$name}.min.css");

        if (! File::exists($source)) {
            $this->warn("{$name}.css не найден.");

            return;
        }

        $css = File::get($source);
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css) ?? $css;
        $css = preg_replace('/\s+/', ' ', $css) ?? $css;
        $css = preg_replace('/\s*([{}:;,])\s*/', '$1', $css) ?? $css;
        $css = trim($css);

        File::put($target, $css);
        $before = filesize($source);
        $after = strlen($css);
        $this->info("CSS: {$name}.min.css (".round($before / 1024, 1).' КБ → '.round($after / 1024, 1).' КБ)');
    }

    private function optimizeStaticImages(ImageUploadService $images): void
    {
        if (! extension_loaded('gd')) {
            $this->warn('GD не включён — статика пропущена.');

            return;
        }

        $map = [
            public_path('images/hero-audio.jpg') => ['max' => 1200, 'quality' => 80],
            public_path('images/admin-avatar.jpg') => ['max' => 400, 'quality' => 82],
            public_path('images/icons/vk.png') => ['max' => 128, 'quality' => 85],
        ];

        $saved = 0;
        foreach ($map as $path => $opts) {
            if (! is_file($path)) {
                continue;
            }
            $saved += $images->optimizePublicFile($path, $opts['max'], $opts['quality']);
            $this->line('  '.basename($path).' — OK');
        }

        $this->info('Статика: сэкономлено '.round($saved / 1024, 1).' КБ');
    }
}
