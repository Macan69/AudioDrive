@extends('layouts.auth')
@section('title', 'Регистрация')
@section('auth-box-class', 'auth-box--register')

@section('auth-title', 'Создать аккаунт')
@section('auth-lead', 'Бонусные баллы начисляются сразу после регистрации')

@section('auth-form')
<form method="POST" action="{{ route('register') }}" class="auth-form">
    @csrf

    @if($errors->any())
        <div class="auth-msg auth-msg--error" role="alert">
            <i class="bi bi-exclamation-circle"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <label class="auth-field">
        <span class="auth-field__label">Имя</span>
        <span class="auth-field__control">
            <i class="bi bi-person" aria-hidden="true"></i>
            <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="Ваше имя"
                autocomplete="name"
                required
                autofocus
                @class(['is-invalid' => $errors->has('name')])
            >
        </span>
    </label>

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
                @class(['is-invalid' => $errors->has('email')])
            >
        </span>
    </label>

    <label class="auth-field">
        <span class="auth-field__label">Телефон <em>необязательно</em></span>
        <span class="auth-field__control">
            <i class="bi bi-telephone" aria-hidden="true"></i>
            <input
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+7 999 000-00-00"
                autocomplete="tel"
                @class(['is-invalid' => $errors->has('phone')])
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
                placeholder="Минимум 6 символов"
                autocomplete="new-password"
                required
                @class(['is-invalid' => $errors->has('password')])
            >
        </span>
    </label>

    <label class="auth-field">
        <span class="auth-field__label">Подтверждение пароля</span>
        <span class="auth-field__control">
            <i class="bi bi-shield-lock" aria-hidden="true"></i>
            <input
                type="password"
                name="password_confirmation"
                placeholder="Повторите пароль"
                autocomplete="new-password"
                required
            >
        </span>
    </label>

    <button type="submit" class="auth-btn">
        Зарегистрироваться <i class="bi bi-arrow-right"></i>
    </button>
</form>
@endsection

@section('auth-perks')
    <li><i class="bi bi-gift-fill"></i> Бонусы</li>
    <li><i class="bi bi-percent"></i> Акции</li>
    <li><i class="bi bi-headset"></i> Поддержка</li>
@endsection
