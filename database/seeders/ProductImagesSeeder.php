<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Services\ProductDemoImageService;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $images = app(ProductDemoImageService::class);

        Product::with('category')->each(function (Product $product) use ($images) {
            $path = $images->ensureForProduct($product);

            if ($path && $product->image !== $path) {
                $product->update(['image' => $path]);
            }
        });
    }
}
