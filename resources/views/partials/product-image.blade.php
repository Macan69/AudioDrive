@php
    $variant = $variant ?? 'card';
    $alt = $alt ?? ($product->name ?? 'Товар');
    $class = match($variant) {
        'card' => 'product-image product-image--card',
        'detail' => 'product-image product-image--detail',
        'thumb' => 'product-image product-image--thumb',
        'admin' => 'product-image product-image--admin',
        default => 'product-image',
    };
@endphp
<div class="{{ $class }}">
    <img src="{{ $product->image_url }}" alt="{{ $alt }}" loading="lazy" decoding="async" referrerpolicy="no-referrer">
</div>
