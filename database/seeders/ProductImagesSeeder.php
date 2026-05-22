<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImagesSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->each(function (Product $product) {
            $relative = 'images/products/'.$product->slug.'.jpg';

            if ($product->image !== $relative) {
                $product->update(['image' => $relative]);
            }
        });
    }
}
