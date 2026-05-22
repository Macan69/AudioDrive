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
    protected $signature = 'app:optimize-project {--images : Сжать загруженные фото товаров}';

    protected $description = 'Очистка кэша, оптимизация Laravel и сжатие изображений';

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
                $this->info('Сэкономлено: '.round($savedBytes / 1024).' КБ');
            }
        }

        $this->newLine();
        $this->comment('Для продакшена: composer install --no-dev --optimize-autoloader');

        return self::SUCCESS;
    }
}
