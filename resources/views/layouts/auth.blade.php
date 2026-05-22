@extends('layouts.app')
@section('main-class', 'main-auth')

@section('content')
<section class="auth-page">
    <div class="auth-page__bg" aria-hidden="true"></div>

    <div class="auth-box @yield('auth-box-class')">
        <nav class="auth-tabs" aria-label="Авторизация">
            <a href="{{ route('login') }}" class="auth-tabs__link {{ request()->routeIs('login') ? 'is-active' : '' }}">Вход</a>
            <a href="{{ route('register') }}" class="auth-tabs__link {{ request()->routeIs('register') ? 'is-active' : '' }}">Регистрация</a>
        </nav>

        <a href="{{ route('home') }}" class="auth-box__logo">
            <span class="site-brand__icon"><i class="bi bi-speaker-fill"></i></span>
            Audio<span class="text-brand">Drive</span>
        </a>

        <header class="auth-box__head">
            <h1 class="auth-box__title">@yield('auth-title')</h1>
            @hasSection('auth-lead')
                <p class="auth-box__lead">@yield('auth-lead')</p>
            @endif
        </header>

        @yield('auth-form')

        @hasSection('auth-perks')
            <ul class="auth-perks">@yield('auth-perks')</ul>
        @endif
    </div>
</section>
@endsection
