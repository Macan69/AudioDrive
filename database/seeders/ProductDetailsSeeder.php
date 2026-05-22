<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Database\Seeder;

class ProductDetailsSeeder extends Seeder
{
    public function run(): void
    {
        $details = [
            'Pioneer TS-WX300A' => [
                'sku' => 'AD-SUB-001',
                'full_description' => 'Активный сабвуфер Pioneer TS-WX300A с встроенным усилителем класса D. Компактный корпус позволяет установить систему в багажнике или под сиденьем. Низкие басы без нагрузки на штатную магнитолу — достаточно подключить питание и сигнал.',
                'features' => ['Встроенный усилитель 300 Вт RMS', 'Регулировка фазы и усиления', 'Компактный корпус с низкой посадкой', 'Дистанционное управление в комплекте', 'Защита от перегрева и КЗ'],
                'warranty_months' => 24,
                'country' => 'Япония',
                'weight' => '8.2 кг',
                'dimensions' => '55×35×28 см',
                'package_contents' => "Сабвуфер Pioneer TS-WX300A\nКабель питания\nКабель RCA\nПульт ДУ\nКрепёжный комплект\nИнструкция",
                'installation' => 'Требуется линия +12В (до 15А), масса и сигнал с головного устройства или ЛВЛ-адаптера. Рекомендуется установка в багажнике на твёрдую поверхность.',
                'extra_attrs' => ['sensitivity' => '86', 'frequency' => '20–200'],
            ],
            'Alpine SWS-12D4' => [
                'sku' => 'AD-SUB-002',
                'full_description' => 'Пассивный сабвуфер Alpine SWS-12D4 с двойной 4-Омной обмоткой. Чистый и плотный бас, совместим с мощными моноблоками. Подходит для коробов закрытого и фазоинверторного типа.',
                'features' => ['Двойная 4-Омная катушка', 'Проприетарный материал Surround', 'Высокая линейность хода', 'Подходит для SQ и SPL сборок'],
                'country' => 'Япония',
                'weight' => '5.4 кг',
                'dimensions' => '31×31×14 см (динамик)',
                'package_contents' => "Сабвуфер Alpine SWS-12D4\nПаспорт изделия\nНаклейки Alpine",
                'installation' => 'Необходим внешний усилитель от 250 Вт RMS на 4 Ом. Установка в короб объёмом 35–45 л.',
                'extra_attrs' => ['sensitivity' => '87', 'frequency' => '26–200'],
            ],
            'Kenwood KAC-M3004' => [
                'sku' => 'AD-AMP-001',
                'full_description' => 'Четырёхканальный усилитель Kenwood KAC-M3004 в компактном корпусе. Идеален для апгрейда штатной системы: передний и задний каналы, возможность мостового режима на сабвуфер.',
                'features' => ['4 канала по 50 Вт RMS', 'Мостовой режим 2×150 Вт', 'Фильтры НЧ/ВЧ', 'Компактный корпус под сиденье'],
                'country' => 'Япония',
                'weight' => '1.8 кг',
                'dimensions' => '20×18×5 см',
                'package_contents' => "Усилитель Kenwood KAC-M3004\nКлеммы и ключ\nИнструкция",
                'installation' => 'Место с вентиляцией, отдельная линия питания 10–15А с предохранителем у АКБ.',
                'extra_attrs' => ['amp_class' => 'AB', 'frequency' => '10–50k'],
            ],
            'JBL GX-A602' => [
                'sku' => 'AD-AMP-002',
                'full_description' => 'Двухканальный усилитель JBL GX-A602 для фронтальной или тыловой акустики. Стабильная работа на 2 Ом, встроенные кроссоверы для простой настройки без процессора.',
                'features' => ['60 Вт×2 RMS при 4 Ом', 'Работа на 2 Ом', 'ВЧ/НЧ фильтры', 'Защита от КЗ и перегрузки'],
                'country' => 'США',
                'weight' => '1.5 кг',
                'package_contents' => "Усилитель JBL GX-A602\nКрепёж\nИнструкция",
                'extra_attrs' => ['amp_class' => 'AB'],
            ],
            'Hertz DSK 165.3' => [
                'sku' => 'AD-SPK-001',
                'full_description' => 'Компонентная акустика Hertz DSK 165.3 — сбалансированный звук для ежедневной езды. Мягкие твиты, чёткая середина, легко разыгрывается штатным головным устройством или небольшим усилителем.',
                'features' => ['Компонентная 2-полосная система', 'Твитер с кроссовером', 'Чувствительность 92 дБ', 'Коаксиальная совместимость с решётками 165 мм'],
                'country' => 'Италия',
                'weight' => '2.1 кг',
                'package_contents' => "2 ВЧ динамика\n2 СЧ динамика\n2 Кроссовера\nКрепёж и сетки",
                'extra_attrs' => ['sensitivity' => '92', 'frequency' => '60–22k'],
            ],
            'Focal 165 AS' => [
                'sku' => 'AD-SPK-002',
                'full_description' => 'Focal 165 AS — флагманская компонентная система с полипропиленовыми диффузорами и алюминиевыми твитерами TNB. Детальная сцена, контроль баса среднего диапазона, рекомендована для SQ-проектов.',
                'features' => ['Французская инженерия Focal', 'Алюминиевый твитер TNB', 'Кроссоверы с регулировкой АЧХ', 'Глубина монтажа СЧ 65 мм'],
                'warranty_months' => 36,
                'country' => 'Франция',
                'weight' => '2.8 кг',
                'package_contents' => "2 midbass Focal\n2 твитера TNB\n2 кроссовера\nШаблоны и крепёж",
                'extra_attrs' => ['sensitivity' => '91', 'frequency' => '55–22k'],
            ],
            'Pioneer DMH-G225BT' => [
                'sku' => 'AD-HU-001',
                'full_description' => 'Мультимедийная магнитола Pioneer DMH-G225BT с сенсорным экраном 6.8", Apple CarPlay, Android Auto и Bluetooth. Поддержка FLAC/WAV, 13-полосный эквалайзер, 4 линейных выхода на усилители.',
                'features' => ['Экран 6.8" capacitive', 'CarPlay / Android Auto', 'Bluetooth 5.0', '4×2.5V предусилитель', 'USB и задняя камера'],
                'country' => 'Япония',
                'weight' => '1.2 кг',
                'dimensions' => '178×100×65 мм (1DIN корпус)',
                'package_contents' => "Головное устройство\nРамка 1DIN\nМикрофон\nUSB-кабель\nИнструкция",
                'installation' => 'Требуется переходная рамка под автомобиль, ISO-разъём или адаптер. Антенна и CAN-шина — по схеме авто.',
            ],
            'Alpine iLX-W650' => [
                'sku' => 'AD-HU-002',
                'full_description' => 'Alpine iLX-W650 — премиальная 2DIN магнитола с коротким шасси, идеальна для автомобилей с ограниченным пространством. Поддержка Hi-Res Audio, продвинутый DSP и Time Alignment.',
                'features' => ['Короткое шасси CHASSIS 2', 'Hi-Res Audio', '9-полосный DSP', 'Wireless CarPlay', 'Поддержка камеры 360°'],
                'warranty_months' => 24,
                'country' => 'Япония',
                'package_contents' => "Магнитола Alpine iLX-W650\nРамки и крепёж\nМикрофон\nКабели",
            ],
            'Кабель акустический 2x2.5мм' => [
                'sku' => 'AD-ACC-001',
                'full_description' => 'Акустический кабель Kicx 2×2.5 мм² OFC для подключения усилителей и динамиков. Медь без кислорода, гибкая изоляция для прокладки в салоне.',
                'features' => ['Сечение 2×2.5 мм²', 'OFC медь', 'Гибкая ПВХ-изоляция', 'Метки метража'],
                'country' => 'Россия',
                'weight' => '0.4 кг / 5 м',
                'package_contents' => "Кабель 5 метров\nТехнический паспорт",
            ],
            'Конденсатор 2 Фарад' => [
                'sku' => 'AD-ACC-002',
                'full_description' => 'Конденсатор Kicx 2 Фарад для стабилизации питания усилителей сабвуфера. Снижает просадки напряжения и защищает электросеть автомобиля при пиковых нагрузках.',
                'features' => ['Ёмкость 2 Ф', 'Цифровой вольтметр', 'Управляемый soft-start', 'Крепёж в комплекте'],
                'country' => 'Россия',
                'weight' => '1.9 кг',
                'package_contents' => "Конденсатор 2F\nКрепёжные ленты\nИнструкция",
            ],
            'Morel Maximo Ultra 602' => [
                'sku' => 'AD-SPK-003',
                'full_description' => 'Morel Maximo Ultra 602 — компонентная система с акцентом на детализацию и мощные магниты. Подходит для установки с отдельным усилителем 80–120 Вт на канал.',
                'features' => ['ВЧ с ферритовым магнитом', 'Кроссоверы 2-полосные', 'Сетки в комплекте', 'Глубина 62 мм'],
                'country' => 'Израиль',
                'extra_attrs' => ['sensitivity' => '90', 'frequency' => '50–22k'],
            ],
            'Sound Digital SD 3000.1' => [
                'sku' => 'AD-AMP-003',
                'full_description' => 'Моноблок Sound Digital SD 3000.1 — эталонный усилитель для соревновательных и Hi-End сабвуферных систем. Класс D, стабильная работа на 1 Ом, компактный корпус при экстремальной мощности.',
                'features' => ['3000 Вт RMS @ 1 Ом', 'Класс D Full-Range', 'DSP-совместимость', 'Защита thermal / UV / OV'],
                'warranty_months' => 24,
                'country' => 'Бразилия',
                'weight' => '2.4 кг',
                'dimensions' => '22×14×5 см',
                'extra_attrs' => ['amp_class' => 'D', 'frequency' => '10–250'],
            ],
        ];

        $attrMap = ProductAttribute::pluck('id', 'slug');

        foreach ($details as $name => $data) {
            $product = Product::where('name', $name)->first();
            if (! $product) {
                continue;
            }

            $extra = $data['extra_attrs'] ?? [];
            unset($data['extra_attrs']);

            $product->update($data);

            foreach ($extra as $attrSlug => $value) {
                if (isset($attrMap[$attrSlug])) {
                    ProductAttributeValue::updateOrCreate(
                        ['product_id' => $product->id, 'product_attribute_id' => $attrMap[$attrSlug]],
                        ['value' => $value]
                    );
                }
            }
        }

        Product::whereNull('sku')->each(function (Product $product, int $i) {
            $product->update([
                'sku' => 'AD-'.str_pad((string) ($product->id), 4, '0', STR_PAD_LEFT),
                'warranty_months' => $product->warranty_months ?: 12,
                'country' => $product->country ?: '—',
                'features' => $product->features ?: ['Официальная гарантия', 'Проверка перед отправкой', 'Консультация по установке'],
                'full_description' => $product->full_description ?: $product->description.' Подробные характеристики уточняйте у менеджера.',
                'package_contents' => $product->package_contents ?: "Товар {$product->name}\nДокументация производителя",
            ]);
        });
    }
}
