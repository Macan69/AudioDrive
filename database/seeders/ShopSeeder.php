<?php

namespace Database\Seeders;

use App\Models\BonusSetting;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedBonusSettings();
        $this->seedUsers();
        $this->seedCategories();
        $this->seedAttributes();
        $this->seedPromotions();
        $this->seedProducts();
    }

    private function seedBonusSettings(): void
    {
        foreach ([
            'points_per_ruble' => '1',
            'ruble_per_point' => '1',
            'max_bonus_percent' => '30',
            'registration_bonus' => '500',
        ] as $key => $value) {
            BonusSetting::set($key, $value);
        }
    }

    private function seedUsers(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@audio.ru'],
            [
                'name' => 'Администратор',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'bonus_points' => 0,
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@audio.ru'],
            [
                'name' => 'Иван Покупатель',
                'password' => Hash::make('password'),
                'phone' => '+7 (999) 123-45-67',
                'bonus_points' => 1200,
            ]
        );
    }

    private function seedCategories(): void
    {
        foreach ([
            ['name' => 'Сабвуферы', 'slug' => 'subwoofers'],
            ['name' => 'Усилители', 'slug' => 'amplifiers'],
            ['name' => 'Динамики', 'slug' => 'speakers'],
            ['name' => 'Головные устройства', 'slug' => 'head-units'],
            ['name' => 'Аксессуары', 'slug' => 'accessories'],
        ] as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], ['name' => $cat['name']]);
        }
    }

    private function seedAttributes(): void
    {
        foreach ([
            ['name' => 'Мощность RMS', 'slug' => 'power_rms', 'unit' => 'Вт', 'sort_order' => 1],
            ['name' => 'Импеданс', 'slug' => 'impedance', 'unit' => 'Ом', 'sort_order' => 2],
            ['name' => 'Размер динамика', 'slug' => 'size', 'unit' => '"', 'sort_order' => 3],
            ['name' => 'Тип крепления', 'slug' => 'mount_type', 'unit' => null, 'sort_order' => 4],
            ['name' => 'Каналы', 'slug' => 'channels', 'unit' => null, 'sort_order' => 5],
        ] as $attr) {
            ProductAttribute::updateOrCreate(
                ['slug' => $attr['slug']],
                $attr + ['type' => 'select', 'is_filterable' => true]
            );
        }
    }

    private function seedPromotions(): void
    {
        Promotion::updateOrCreate(
            ['name' => 'Скидка 10% от 15000 ₽'],
            [
                'code' => null,
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 15000,
                'auto_apply' => true,
                'is_active' => true,
            ]
        );

        Promotion::updateOrCreate(
            ['code' => 'AUDIO500'],
            [
                'name' => 'Промокод AUDIO500',
                'type' => 'fixed',
                'value' => 500,
                'min_order_amount' => 3000,
                'bonus_points_reward' => 100,
                'auto_apply' => false,
                'is_active' => true,
            ]
        );
    }

    private function seedProducts(): void
    {
        $products = [
            ['category' => 'subwoofers', 'name' => 'Pioneer TS-WX300A', 'brand' => 'Pioneer', 'price' => 12990, 'stock' => 24, 'attrs' => ['power_rms' => '300', 'impedance' => '4', 'size' => '12', 'mount_type' => 'активный']],
            ['category' => 'subwoofers', 'name' => 'Alpine SWS-12D4', 'brand' => 'Alpine', 'price' => 8990, 'stock' => 18, 'attrs' => ['power_rms' => '250', 'impedance' => '4', 'size' => '12', 'mount_type' => 'пассивный']],
            ['category' => 'amplifiers', 'name' => 'Kenwood KAC-M3004', 'brand' => 'Kenwood', 'price' => 7490, 'stock' => 30, 'attrs' => ['power_rms' => '50x4', 'channels' => '4', 'impedance' => '4']],
            ['category' => 'amplifiers', 'name' => 'JBL GX-A602', 'brand' => 'JBL', 'price' => 5990, 'stock' => 22, 'attrs' => ['power_rms' => '60x2', 'channels' => '2', 'impedance' => '2-8']],
            ['category' => 'speakers', 'name' => 'Hertz DSK 165.3', 'brand' => 'Hertz', 'price' => 4590, 'stock' => 35, 'attrs' => ['power_rms' => '80', 'size' => '6.5', 'impedance' => '4']],
            ['category' => 'speakers', 'name' => 'Focal 165 AS', 'brand' => 'Focal', 'price' => 18990, 'old_price' => 21990, 'stock' => 12, 'attrs' => ['power_rms' => '120', 'size' => '6.5', 'impedance' => '4'], 'featured' => true],
            ['category' => 'head-units', 'name' => 'Pioneer DMH-G225BT', 'brand' => 'Pioneer', 'price' => 15990, 'stock' => 15, 'attrs' => ['channels' => '4'], 'featured' => true],
            ['category' => 'head-units', 'name' => 'Alpine iLX-W650', 'brand' => 'Alpine', 'price' => 24990, 'stock' => 8, 'attrs' => ['channels' => '4'], 'featured' => true],
            ['category' => 'accessories', 'name' => 'Кабель акустический 2x2.5мм', 'brand' => 'Kicx', 'price' => 890, 'stock' => 50, 'attrs' => []],
            ['category' => 'accessories', 'name' => 'Конденсатор 2 Фарад', 'brand' => 'Kicx', 'price' => 3490, 'stock' => 20, 'attrs' => []],
            ['category' => 'speakers', 'name' => 'Morel Maximo Ultra 602', 'brand' => 'Morel', 'price' => 12490, 'stock' => 14, 'attrs' => ['power_rms' => '100', 'size' => '6.5', 'impedance' => '4']],
            ['category' => 'amplifiers', 'name' => 'Sound Digital SD 3000.1', 'brand' => 'Sound Digital', 'price' => 28990, 'stock' => 6, 'attrs' => ['power_rms' => '3000', 'channels' => '1', 'impedance' => '1']],
        ];

        $attrMap = ProductAttribute::pluck('id', 'slug');

        foreach ($products as $p) {
            $category = Category::where('slug', $p['category'])->firstOrFail();
            $slug = Str::slug($p['name']);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $p['name'],
                    'brand' => $p['brand'],
                    'description' => "Профессиональная автомобильная акустика {$p['brand']}. Модель {$p['name']} — отличный выбор для качественного звука в автомобиле.",
                    'price' => $p['price'],
                    'old_price' => $p['old_price'] ?? null,
                    'stock' => $p['stock'],
                    'is_active' => true,
                    'is_featured' => $p['featured'] ?? false,
                ]
            );

            foreach ($p['attrs'] as $attrSlug => $value) {
                ProductAttributeValue::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'product_attribute_id' => $attrMap[$attrSlug],
                    ],
                    ['value' => $value]
                );
            }
        }
    }
}
