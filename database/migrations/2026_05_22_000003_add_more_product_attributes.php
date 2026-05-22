<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $attrs = [
            ['name' => 'Чувствительность', 'slug' => 'sensitivity', 'unit' => 'дБ', 'sort_order' => 6],
            ['name' => 'Частотный диапазон', 'slug' => 'frequency', 'unit' => 'Гц', 'sort_order' => 7],
            ['name' => 'Класс усилителя', 'slug' => 'amp_class', 'unit' => null, 'sort_order' => 8],
        ];

        foreach ($attrs as $attr) {
            if (! DB::table('product_attributes')->where('slug', $attr['slug'])->exists()) {
                DB::table('product_attributes')->insert([
                    ...$attr,
                    'type' => 'select',
                    'is_filterable' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('product_attributes')->whereIn('slug', ['sensitivity', 'frequency', 'amp_class'])->delete();
    }
};
