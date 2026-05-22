@extends('layouts.app')
@section('title', 'Личный кабинет')

@section('content')
<div class="account-page">
    <div class="container">
        <header class="account-header">
            <div>
                <span class="account-header__label">AudioDrive</span>
                <h1 class="account-header__title">Личный кабинет</h1>
            </div>
            @if($user->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn btn-brand">
                    <i class="bi bi-speedometer2"></i> Админ-панель
                </a>
            @endif
        </header>

        <div class="account-layout">
            <aside class="account-sidebar">
                <div class="account-profile">
                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="account-profile__avatar">
                    <h2 class="account-profile__name">{{ $user->name }}</h2>
                    <p class="account-profile__email">{{ $user->email }}</p>
                    @if($user->phone)
                        <p class="account-profile__phone"><i class="bi bi-telephone"></i> {{ $user->phone }}</p>
                    @endif
                    @if($user->isAdmin())
                        <span class="account-badge account-badge--admin"><i class="bi bi-shield-lock"></i> Администратор</span>
                    @else
                        <div class="account-bonus">
                            <span class="account-bonus__value">{{ number_format($user->bonus_points, 0, '', ' ') }}</span>
                            <span class="account-bonus__label">бонусных баллов</span>
                        </div>
                    @endif
                </div>

                <div class="account-card">
                    <h3 class="account-card__title"><i class="bi bi-pencil-square"></i> Профиль</h3>
                    <form method="POST" action="{{ route('account.profile') }}" class="account-form">
                        @csrf
                        <label class="account-form__field">
                            <span>Имя</span>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </label>
                        <label class="account-form__field">
                            <span>Телефон</span>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+7 999 000-00-00">
                        </label>
                        <button type="submit" class="btn btn-brand w-100">Сохранить</button>
                    </form>
                </div>
            </aside>

            <main class="account-main">
                @if($user->isAdmin())
                    @include('account.partials.admin-panel')
                @else
                    @include('account.partials.user-panel', ['orders' => $orders, 'transactions' => $transactions])
                @endif
            </main>
        </div>
    </div>
</div>
@endsection
