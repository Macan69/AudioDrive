<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ProductDemoImageService;
use Illuminate\Console\Command;

class GenerateProductImagesCommand extends Command
{
    protected $signature = 'products:generate-images {--force : Перезаписать существующие файлы}';

    protected $description = 'Сгенерировать локальные JPG с названиями товаров (public/images/products)';

    public function handle(ProductDemoImageService $generator): int
    {
        if (! extension_loaded('gd')) {
            $this->error('Включите расширение PHP GD.');

            return self::FAILURE;
        }

        $force = (bool) $this->option('force');
        $count = 0;

        Product::query()->with('category')->each(function (Product $product) use ($generator, $force, &$count) {
            $relative = 'images/products/'.$product->slug.'.jpg';
            $absolute = public_path($relative);

            if (! $force && is_file($absolute)) {
                return;
            }

            $categorySlug = $product->category?->slug ?? 'accessories';
            $generator->generate($product->slug, $product->name, $product->brand, $categorySlug);
            $product->update(['image' => $relative]);
            $count++;
            $this->line($product->name.' → '.$relative);
        });

        $generator->generateHero();
        $this->info("Готово: {$count} товар(ов), hero-audio.jpg обновлён.");

        return self::SUCCESS;
    }
}
