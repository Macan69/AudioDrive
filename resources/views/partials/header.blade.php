@php
    $cartCount = app(\App\Services\CartService::class)->count();
    $currentCategory = request('category');
@endphp
<header class="site-header sticky-top">
    <nav class="navbar navbar-expand-lg site-navbar">
        <div class="container">
            <a class="site-brand" href="{{ route('home') }}">
                <span class="site-brand__icon"><i class="bi bi-speaker-fill"></i></span>
                <span class="site-brand__text">Audio<span class="site-brand__accent">Drive</span></span>
            </a>

            <button class="navbar-toggler site-navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Меню">
                <span class="site-navbar-toggler__bar"></span>
                <span class="site-navbar-toggler__bar"></span>
                <span class="site-navbar-toggler__bar"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav site-nav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('catalog.index') && !$currentCategory ? 'active' : '' }}" href="{{ route('catalog.index') }}">Каталог</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentCategory === 'subwoofers' ? 'active' : '' }}" href="{{ route('catalog.index', ['category' => 'subwoofers']) }}">Сабвуферы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentCategory === 'amplifiers' ? 'active' : '' }}" href="{{ route('catalog.index', ['category' => 'amplifiers']) }}">Усилители</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $currentCategory === 'speakers' ? 'active' : '' }}" href="{{ route('catalog.index', ['category' => 'speakers']) }}">Динамики</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">О нас</a>
                    </li>
                </ul>

                <div class="site-header__actions">
                    <form class="site-search" action="{{ route('catalog.index') }}" method="GET" role="search">
                        <i class="bi bi-search site-search__icon" aria-hidden="true"></i>
                        <input
                            class="site-search__input"
                            type="search"
                            name="search"
                            placeholder="Поиск товаров..."
                            value="{{ request('search') }}"
                            aria-label="Поиск"
                        >
                    </form>

                    <a class="site-cart" href="{{ route('cart.index') }}" aria-label="Корзина{{ $cartCount ? ', '.$cartCount.' товаров' : '' }}">
                        <i class="bi bi-cart3"></i>
                        @if($cartCount)
                            <span class="site-cart__badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                        @endif
                    </a>

                    <div class="site-auth">
                        @auth
                            <a class="site-auth__link" href="{{ route('account.index') }}" title="Личный кабинет">
                                <i class="bi bi-person"></i>
                                <span class="d-none d-xl-inline">Кабинет</span>
                            </a>
                            @if(auth()->user()->isAdmin())
                                <a class="site-auth__link site-auth__link--admin" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-gear"></i>
                                    <span class="d-none d-xl-inline">Админ</span>
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="site-auth__btn site-auth__btn--ghost">Выход</button>
                            </form>
                        @else
                            <a class="site-auth__link" href="{{ route('login') }}">Вход</a>
                            <a class="site-auth__btn site-auth__btn--primary" href="{{ route('register') }}">Регистрация</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
