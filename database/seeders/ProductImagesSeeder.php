<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Services\ProductImageCdn;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        $cdn = app(ProductImageCdn::class);

        Product::query()->each(function (Product $product) use ($cdn) {
            $key = $cdn->keyForSlug($product->slug);

            if ($product->image !== $key) {
                $product->update(['image' => $key]);
            }
        });
    }
}
