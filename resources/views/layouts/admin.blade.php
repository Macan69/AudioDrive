<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Админ') — AudioDrive</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ \App\Providers\AppServiceProvider::adminStylesheet() }}" rel="stylesheet">
</head>
<body class="admin-body">
@php
    $adminNav = [
        ['route' => 'admin.dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Дашборд'],
        ['route' => 'admin.products.*', 'icon' => 'bi-box-seam', 'label' => 'Товары'],
        ['route' => 'admin.categories.*', 'icon' => 'bi-tags', 'label' => 'Категории'],
        ['route' => 'admin.orders.*', 'icon' => 'bi-bag-check', 'label' => 'Заказы'],
        ['route' => 'admin.promotions.*', 'icon' => 'bi-percent', 'label' => 'Акции'],
        ['route' => 'admin.bonus.*', 'icon' => 'bi-gift', 'label' => 'Бонусы'],
    ];
@endphp
<div class="admin-app">
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__brand">
            <i class="bi bi-speaker-fill"></i>
            <span>Audio<em>Drive</em> Admin</span>
        </a>

        <nav class="admin-nav">
            @foreach($adminNav as $item)
                <a
                    href="{{ route(str_contains($item['route'], '*') ? str_replace('.*', '.index', $item['route']) : $item['route']) }}"
                    class="admin-nav__link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}"
                >
                    <i class="bi {{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="admin-sidebar__foot">
            <a href="{{ route('account.index') }}"><i class="bi bi-person"></i> Кабинет</a>
            <a href="{{ route('home') }}"><i class="bi bi-house"></i> На сайт</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="is-danger"><i class="bi bi-box-arrow-right"></i> Выход</button>
            </form>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <p class="admin-topbar__title">@yield('title', 'Панель управления')</p>
            <span class="admin-topbar__user">{{ auth()->user()->name }}</span>
        </header>

        <div class="admin-content">
            @if(session('success'))
                <div class="admin-alert" role="alert">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="admin-alert" style="background:#f8d7da;color:#842029;border-color:#f5c2c7" role="alert">
                    <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
@stack('scripts')
</body>
</html>
