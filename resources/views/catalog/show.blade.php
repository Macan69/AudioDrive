@extends('layouts.app')
@section('title', $product->name)

@section('content')
<section class="catalog-page catalog-page--product">
    <div class="container">
        <nav class="catalog-breadcrumb" aria-label="Навигация">
            <a href="{{ route('catalog.index') }}">Каталог</a>
            <i class="bi bi-chevron-right"></i>
            <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
            <i class="bi bi-chevron-right"></i>
            <span>{{ $product->name }}</span>
        </nav>

        <div class="product-detail">
            <div class="product-detail__gallery">
                <div class="product-detail__image-wrap">
                    @include('partials.product-image', ['product' => $product, 'variant' => 'detail'])
                </div>
            </div>

            <div class="product-detail__info">
                <div class="product-detail__tags">
                    <span class="catalog-card__brand">{{ $product->brand }}</span>
                    @if($product->sku)<span class="product-detail__sku">Арт. {{ $product->sku }}</span>@endif
                    @if($product->is_featured)<span class="catalog-card__badge catalog-card__badge--hit">Хит</span>@endif
                </div>

                <h1 class="product-detail__title">{{ $product->name }}</h1>

                <div class="product-detail__price-box">
                    <div class="product-detail__price">
                        {{ number_format($product->price, 0, '', ' ') }} <span>₽</span>
                    </div>
                    @if($product->hasDiscount())
                        <span class="product-detail__old-price">{{ number_format($product->old_price, 0, '', ' ') }} ₽</span>
                        <span class="product-detail__discount">−{{ round((1 - $product->price / $product->old_price) * 100) }}%</span>
                    @endif
                </div>

                <ul class="product-detail__highlights">
                    <li><i class="bi bi-box-seam"></i> В наличии: <strong>{{ $product->stock }} шт.</strong></li>
                    <li><i class="bi bi-shield-check"></i> Гарантия: <strong>{{ $product->warranty_months }} мес.</strong></li>
                    @if($product->country)<li><i class="bi bi-geo-alt"></i> {{ $product->country }}</li>@endif
                    @if($product->weight)<li><i class="bi bi-speedometer2"></i> {{ $product->weight }}</li>@endif
                    @if($product->dimensions)<li><i class="bi bi-rulers"></i> {{ $product->dimensions }}</li>@endif
                </ul>

                <p class="product-detail__lead">{{ $product->description }}</p>

                @if($product->features && count($product->features))
                    <ul class="product-detail__features">
                        @foreach($product->features as $feature)
                            <li><i class="bi bi-check-circle-fill"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>
                @endif

                <form action="{{ route('cart.add') }}" method="POST" class="product-detail__buy">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <label class="product-detail__qty">
                        <span>Кол-во</span>
                        <input type="number" name="quantity" value="1" min="1" max="{{ max(1, $product->stock) }}" class="form-control">
                    </label>
                    <button type="submit" class="btn btn-brand btn-lg product-detail__cart-btn" @if($product->stock < 1) disabled @endif>
                        <i class="bi bi-cart-plus"></i> В корзину
                    </button>
                </form>
            </div>
        </div>

        <div class="product-tabs">
            <div class="product-tabs__nav" role="tablist">
                <button class="product-tabs__btn active" type="button" data-bs-toggle="tab" data-bs-target="#tab-specs">Характеристики</button>
                <button class="product-tabs__btn" type="button" data-bs-toggle="tab" data-bs-target="#tab-desc">Описание</button>
                <button class="product-tabs__btn" type="button" data-bs-toggle="tab" data-bs-target="#tab-package">Комплектация</button>
                @if($product->installation)
                    <button class="product-tabs__btn" type="button" data-bs-toggle="tab" data-bs-target="#tab-install">Установка</button>
                @endif
            </div>
            <div class="tab-content product-tabs__content">
                <div class="tab-pane fade show active" id="tab-specs">
                    @if($product->attributeValues->isNotEmpty())
                        <dl class="product-specs">
                            @foreach($product->attributeValues as $av)
                                <div class="product-specs__row">
                                    <dt>{{ $av->attribute->name }}</dt>
                                    <dd>{{ $av->value }}@if($av->attribute->unit) {{ $av->attribute->unit }}@endif</dd>
                                </div>
                            @endforeach
                            @if($product->sku)
                                <div class="product-specs__row"><dt>Артикул</dt><dd>{{ $product->sku }}</dd></div>
                            @endif
                            @if($product->weight)
                                <div class="product-specs__row"><dt>Вес</dt><dd>{{ $product->weight }}</dd></div>
                            @endif
                            @if($product->dimensions)
                                <div class="product-specs__row"><dt>Габариты</dt><dd>{{ $product->dimensions }}</dd></div>
                            @endif
                            <div class="product-specs__row"><dt>Гарантия</dt><dd>{{ $product->warranty_months }} месяцев</dd></div>
                            @if($product->country)
                                <div class="product-specs__row"><dt>Страна</dt><dd>{{ $product->country }}</dd></div>
                            @endif
                        </dl>
                    @else
                        <p class="text-muted mb-0">Технические характеристики уточняйте у менеджера.</p>
                    @endif
                </div>
                <div class="tab-pane fade" id="tab-desc">
                    <div class="product-tabs__text">{{ $product->full_description ?? $product->description }}</div>
                </div>
                <div class="tab-pane fade" id="tab-package">
                    @if($product->package_contents)
                        <div class="product-tabs__text product-tabs__pre">{{ $product->package_contents }}</div>
                    @else
                        <p class="text-muted mb-0">Товар и документация производителя.</p>
                    @endif
                </div>
                @if($product->installation)
                    <div class="tab-pane fade" id="tab-install">
                        <div class="product-tabs__text">{{ $product->installation }}</div>
                    </div>
                @endif
            </div>
        </div>

        @if($related->isNotEmpty())
            <section class="catalog-related">
                <h2 class="catalog-related__title">Похожие товары</h2>
                <div class="catalog-grid catalog-grid--4">
                    @foreach($related as $relatedProduct)
                        @include('partials.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</section>
@endsection
