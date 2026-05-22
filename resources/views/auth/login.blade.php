@extends('layouts.auth')
@section('title', 'Вход')

@section('auth-title', 'Добро пожаловать')
@section('auth-lead', 'Войдите, чтобы управлять заказами и бонусами')

@section('auth-form')
<form method="POST" action="{{ route('login') }}" class="auth-form">
    @csrf

    @if($errors->has('email'))
        <div class="auth-msg auth-msg--error" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            <span>{{ $errors->first('email') }}</span>
        </div>
    @endif

    <label class="auth-field">
        <span class="auth-field__label">Email</span>
        <span class="auth-field__control">
            <i class="bi bi-envelope" aria-hidden="true"></i>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="name@mail.ru"
                autocomplete="email"
                required
                autofocus
                @class(['is-invalid' => $errors->has('email')])
            >
        </span>
    </label>

    <label class="auth-field">
        <span class="auth-field__label">Пароль</span>
        <span class="auth-field__control">
            <i class="bi bi-lock" aria-hidden="true"></i>
            <input
                type="password"
                name="password"
                placeholder="Введите пароль"
                autocomplete="current-password"
                required
            >
        </span>
    </label>

    <label class="auth-remember">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <span class="auth-remember__mark"></span>
        <span>Запомнить меня</span>
    </label>

    <button type="submit" class="auth-btn">
        Войти <i class="bi bi-arrow-right"></i>
    </button>
</form>
@endsection

@section('auth-perks')
    <li><i class="bi bi-bag-check"></i> Заказы</li>
    <li><i class="bi bi-gift"></i> Бонусы</li>
    <li><i class="bi bi-lightning-charge"></i> Быстрый checkout</li>
@endsection
