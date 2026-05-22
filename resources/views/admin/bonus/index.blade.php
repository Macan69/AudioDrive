@extends('layouts.admin')
@section('title', 'Бонусная программа')

@section('content')
<div class="admin-page-head">
    <div>
        <h1>Бонусная программа</h1>
        <p>Настройки начисления и списания баллов</p>
    </div>
</div>

<div class="admin-card admin-form-card">
    <div class="admin-card__head">
        <h2><i class="bi bi-gift" style="color:var(--admin-brand)"></i> Параметры</h2>
    </div>
    <div class="admin-card__body">
        <form method="POST" action="{{ route('admin.bonus.update') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Баллов за 1 ₽ покупки</label>
                    <input type="number" step="0.1" name="points_per_ruble" class="form-control" value="{{ $settings['points_per_ruble'] }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">₽ за 1 бонусный балл при списании</label>
                    <input type="number" step="0.01" name="ruble_per_point" class="form-control" value="{{ $settings['ruble_per_point'] }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Макс. % заказа бонусами</label>
                    <input type="number" name="max_bonus_percent" class="form-control" value="{{ $settings['max_bonus_percent'] }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Бонус при регистрации</label>
                    <input type="number" name="registration_bonus" class="form-control" value="{{ $settings['registration_bonus'] }}" required>
                </div>
            </div>
            <button type="submit" class="btn-admin-primary mt-4">
                <i class="bi bi-check-lg"></i> Сохранить настройки
            </button>
        </form>
    </div>
</div>
@endsection
