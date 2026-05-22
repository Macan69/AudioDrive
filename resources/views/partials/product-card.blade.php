<article class="catalog-card">
    <a href="{{ route('catalog.show', $product->slug) }}" class="catalog-card__image">
        @include('partials.product-image', ['product' => $product, 'variant' => 'card'])
        @if($product->hasDiscount())
            <span class="catalog-card__badge">−{{ round((1 - $product->price / $product->old_price) * 100) }}%</span>
        @elseif($product->is_featured)
            <span class="catalog-card__badge catalog-card__badge--hit">Хит</span>
        @endif
    </a>
    <div class="catalog-card__body">
        <span class="catalog-card__brand">{{ $product->brand }}</span>
        <h3 class="catalog-card__title">
            <a href="{{ route('catalog.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>
        <span class="catalog-card__category">{{ $product->category->name }}</span>
        @if($product->relationLoaded('attributeValues') && $product->attributeValues->isNotEmpty())
            <p class="catalog-card__specs">
                {{ $product->attributeValues->take(2)->map(fn ($av) => $av->attribute->name.': '.$av->value)->join(' · ') }}
            </p>
        @endif
        <div class="catalog-card__footer">
            <div class="catalog-card__price">
                @if($product->hasDiscount())
                    <span class="catalog-card__old">{{ number_format($product->old_price, 0, '', ' ') }} ₽</span>
                @endif
                <strong>{{ number_format($product->price, 0, '', ' ') }} ₽</strong>
            </div>
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="catalog-card__cart" @if($product->stock < 1) disabled title="Нет в наличии" @endif aria-label="В корзину">
                    <i class="bi bi-cart-plus"></i>
                </button>
            </form>
        </div>
    </div>
</article>
