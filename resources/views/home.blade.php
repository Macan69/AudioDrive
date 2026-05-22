@extends('layouts.app')
@section('title', 'Главная')
@section('main-class', 'main-home')

@section('content')
@php
    $categoryIcons = [
        'subwoofers' => ['icon' => 'bi-speaker-fill', 'gradient' => 'from-sub'],
        'amplifiers' => ['icon' => 'bi-lightning-charge-fill', 'gradient' => 'from-amp'],
        'speakers' => ['icon' => 'bi-music-note-beamed', 'gradient' => 'from-spk'],
        'head-units' => ['icon' => 'bi-display-fill', 'gradient' => 'from-hu'],
        'accessories' => ['icon' => 'bi-plug-fill', 'gradient' => 'from-acc'],
    ];
@endphp

<div class="home-page">
    <section class="home-hero">
        <div class="home-hero__glow home-hero__glow--1"></div>
        <div class="home-hero__glow home-hero__glow--2"></div>
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-7">
                    <span class="home-hero__badge">
                        <i class="bi bi-stars"></i> Премиум автозвук
                    </span>
                    <h1 class="home-hero__title">
                        Звук, который<br>
                        <span class="home-hero__accent">движет</span> вами
                    </h1>
                    <p class="home-hero__lead">
                        Сабвуферы, усилители, динамики и магнитолы от Pioneer, Alpine, JBL, Focal и других брендов — с доставкой по России.
                    </p>
                    <div class="home-hero__actions">
                        <a href="{{ route('catalog.index') }}" class="btn btn-brand btn-lg px-4">
                            <i class="bi bi-grid me-2"></i>Каталог
                        </a>
                        <a href="{{ route('about') }}" class="btn btn-hero-outline btn-lg px-4">
                            О магазине
                        </a>
                    </div>
                    <div class="home-hero__stats">
                        <div class="home-hero__stat">
                            <strong>500+</strong>
                            <span>товаров</span>
                        </div>
                        <div class="home-hero__stat">
                            <strong>12 мес.</strong>
                            <span>гарантия</span>
                        </div>
                        <div class="home-hero__stat">
                            <strong>24/7</strong>
                            <span>поддержка</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="home-hero__image-wrap">
                        <img
                            src="{{ asset('images/hero-audio.jpg') }}"
                            alt="Автомобильная акустика — сабвуферы и усилители"
                            class="home-hero__image"
                            width="560"
                            height="420"
                            loading="eager"
                            fetchpriority="high"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home-trust">
        <div class="container">
            <div class="home-trust__grid">
                <div class="home-trust__item">
                    <i class="bi bi-truck"></i>
                    <div>
                        <strong>Быстрая доставка</strong>
                        <span>По всей России</span>
                    </div>
                </div>
                <div class="home-trust__item">
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <strong>Официальная гарантия</strong>
                        <span>На все товары</span>
                    </div>
                </div>
                <div class="home-trust__item">
                    <i class="bi bi-gift"></i>
                    <div>
                        <strong>Бонусная программа</strong>
                        <span>Баллы за покупки</span>
                    </div>
                </div>
                <div class="home-trust__item">
                    <i class="bi bi-headset"></i>
                    <div>
                        <strong>Консультация</strong>
                        <span>Поможем с подбором</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container home-body">
        @if($promotions->isNotEmpty())
        <div class="home-promo">
            <div class="home-promo__icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div class="home-promo__text">
                <strong>Активные акции</strong>
                <span>
                    @foreach($promotions as $p){{ $p->name }}@if(!$loop->last) · @endif @endforeach
                    — применяются автоматически
                </span>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-dark">Выбрать товар</a>
        </div>
        @endif

        <section class="home-section">
            <div class="home-section__head">
                <div>
                    <span class="home-section__label">Каталог</span>
                    <h2 class="home-section__title">Категории товаров</h2>
                </div>
            </div>
            <div class="row g-3 g-md-4">
                @foreach($categories as $cat)
                @php $meta = $categoryIcons[$cat->slug] ?? ['icon' => 'bi-box-seam-fill', 'gradient' => 'from-default']; @endphp
                <div class="col-6 col-md-4 col-lg">
                    <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="home-category {{ $meta['gradient'] }}">
                        <div class="home-category__icon">
                            <i class="bi {{ $meta['icon'] }}"></i>
                        </div>
                        <div class="home-category__body">
                            <h3>{{ $cat->name }}</h3>
                            <span>{{ $cat->products_count }} позиций</span>
                        </div>
                        <i class="bi bi-arrow-right home-category__arrow"></i>
                    </a>
                </div>
                @endforeach
            </div>
        </section>

        <section class="home-section home-section--featured">
            <div class="home-section__head">
                <div>
                    <span class="home-section__label">Популярное</span>
                    <h2 class="home-section__title">Хиты продаж</h2>
                </div>
                <a href="{{ route('catalog.index') }}" class="home-section__link">
                    Все товары <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($featured as $product)
                <div class="col-10 col-sm-6 col-md-4 col-lg-3">
                    @include('partials.product-card', ['product' => $product])
                </div>
                @empty
                <div class="col-12">
                    <div class="home-empty">Скоро появятся новые хиты — загляните в <a href="{{ route('catalog.index') }}">каталог</a>.</div>
                </div>
                @endforelse
            </div>
        </section>

        <section class="home-cta">
            <div class="home-cta__inner">
                <h2>Нужна помощь с подбором акустики?</h2>
                <p>Расскажем, что подойдёт под ваш автомобиль и бюджет. Бесплатная консультация в шоуруме или онлайн.</p>
                <div class="d-flex flex-wrap gap-2 justify-content-center">
                    <a href="{{ route('about') }}#contacts" class="btn btn-brand">Связаться</a>
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-light">Смотреть каталог</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
