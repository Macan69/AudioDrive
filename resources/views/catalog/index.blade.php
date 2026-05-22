@extends('layouts.app')
@section('title', 'Каталог')

@php
    $activeCategory = $categories->firstWhere('slug', request('category'));
    $hasFilters = request()->hasAny(['category', 'brand', 'price_min', 'price_max', 'search']) || collect(request('attr', []))->flatten()->filter()->isNotEmpty();
@endphp

@section('content')
<section class="catalog-page">
    <div class="container">
        <header class="catalog-header">
            <div>
                <span class="catalog-header__label">AudioDrive</span>
                <h1 class="catalog-header__title">
                    @if(request('search'))
                        Поиск: «{{ request('search') }}»
                    @elseif($activeCategory)
                        {{ $activeCategory->name }}
                    @else
                        Каталог товаров
                    @endif
                </h1>
                <p class="catalog-header__lead">Автомобильная акустика с доставкой по России</p>
            </div>
            <button class="btn btn-catalog-filter d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#catalogFilters">
                <i class="bi bi-sliders"></i> Фильтры
            </button>
        </header>

        @if($hasFilters)
            <div class="catalog-active-filters">
                <span class="catalog-active-filters__label">Фильтры:</span>
                @if(request('search'))
                    <span class="catalog-chip">Поиск: {{ request('search') }}</span>
                @endif
                @if($activeCategory)
                    <span class="catalog-chip">{{ $activeCategory->name }}</span>
                @endif
                @foreach((array) request('brand', []) as $brand)
                    <span class="catalog-chip">{{ $brand }}</span>
                @endforeach
                <a href="{{ route('catalog.index') }}" class="catalog-active-filters__reset">Сбросить все</a>
            </div>
        @endif

        <div class="catalog-layout">
            <aside class="catalog-sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="catalogFilters" aria-labelledby="catalogFiltersLabel">
                <div class="offcanvas-header d-lg-none">
                    <h2 class="offcanvas-title" id="catalogFiltersLabel">Фильтры</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#catalogFilters"></button>
                </div>
                <div class="offcanvas-body catalog-filters">
                    <form method="GET" action="{{ route('catalog.index') }}" class="catalog-filters__form">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}">
                        @endif

                        <div class="catalog-filter-group">
                            <h3 class="catalog-filter-group__title">Категория</h3>
                            <label class="catalog-filter-option">
                                <input type="radio" name="category" value="" @checked(!request('category'))>
                                <span>Все категории</span>
                            </label>
                            @foreach($categories as $cat)
                                <label class="catalog-filter-option">
                                    <input type="radio" name="category" value="{{ $cat->slug }}" @checked(request('category') == $cat->slug)>
                                    <span>{{ $cat->name }} <em>{{ $cat->products_count }}</em></span>
                                </label>
                            @endforeach
                        </div>

                        @if($brands->isNotEmpty())
                            <div class="catalog-filter-group">
                                <h3 class="catalog-filter-group__title">Бренд</h3>
                                <div class="catalog-filter-list catalog-filter-list--scroll">
                                    @foreach($brands as $brand)
                                        <label class="catalog-filter-option">
                                            <input type="checkbox" name="brand[]" value="{{ $brand }}" @checked(in_array($brand, (array) request('brand', [])))>
                                            <span>{{ $brand }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="catalog-filter-group">
                            <h3 class="catalog-filter-group__title">Цена, ₽</h3>
                            <div class="catalog-filter-price">
                                <input type="number" name="price_min" class="form-control" placeholder="от" value="{{ request('price_min') }}">
                                <span>—</span>
                                <input type="number" name="price_max" class="form-control" placeholder="до" value="{{ request('price_max') }}">
                            </div>
                        </div>

                        @foreach($filterAttributes as $attr)
                            @if($attr->filter_values->isNotEmpty())
                                <div class="catalog-filter-group">
                                    <h3 class="catalog-filter-group__title">
                                        {{ $attr->name }}@if($attr->unit)<small>({{ $attr->unit }})</small>@endif
                                    </h3>
                                    <div class="catalog-filter-list">
                                        @foreach($attr->filter_values as $val)
                                            <label class="catalog-filter-option">
                                                <input type="checkbox" name="attr[{{ $attr->slug }}][]" value="{{ $val }}"
                                                    @checked(in_array($val, (array) request("attr.{$attr->slug}", [])))>
                                                <span>{{ $val }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        <div class="catalog-filters__actions">
                            <button type="submit" class="btn btn-brand w-100">Применить</button>
                            <a href="{{ route('catalog.index', request('search') ? ['search' => request('search')] : []) }}" class="btn btn-catalog-outline w-100">Сбросить</a>
                        </div>
                    </form>
                </div>
            </aside>

            <div class="catalog-main">
                <div class="catalog-toolbar">
                    <span class="catalog-toolbar__count">Найдено: <strong>{{ $products->total() }}</strong></span>
                    <label class="catalog-sort">
                        <span>Сортировка</span>
                        <select class="form-select" onchange="location.href=this.value">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" @selected(request('sort', 'newest') == 'newest')>Новинки</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" @selected(request('sort') == 'price_asc')>Цена ↑</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" @selected(request('sort') == 'price_desc')>Цена ↓</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}" @selected(request('sort') == 'name')>По названию</option>
                        </select>
                    </label>
                </div>

                @if($products->isEmpty())
                    <div class="catalog-empty">
                        <i class="bi bi-search"></i>
                        <h2>Товары не найдены</h2>
                        <p>Попробуйте изменить фильтры или сбросить параметры поиска.</p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-brand">Показать весь каталог</a>
                    </div>
                @else
                    <div class="catalog-grid">
                        @foreach($products as $product)
                            @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    <div class="catalog-pagination">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
