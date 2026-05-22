@extends('layouts.admin')
@section('title', $promotion->exists ? 'Редактировать акцию' : 'Новая акция')
@section('content')
<div class="admin-page-head">
    <div>
        <h1>{{ $promotion->exists ? 'Редактировать акцию' : 'Новая акция' }}</h1>
        <p>Промокод или автоматическая скидка</p>
    </div>
    <a href="{{ route('admin.promotions.index') }}" class="btn-admin-ghost"><i class="bi bi-arrow-left"></i> К списку</a>
</div>

<div class="admin-card admin-form-card">
    <div class="admin-card__body">
<form method="POST" action="{{ $promotion->exists ? route('admin.promotions.update', $promotion) : route('admin.promotions.store') }}">
    @csrf @if($promotion->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-12"><label class="form-label">Название</label><input type="text" name="name" class="form-control" value="{{ old('name', $promotion->name) }}" required></div>
        <div class="col-12"><label class="form-label">Промокод (пусто = только авто)</label><input type="text" name="code" class="form-control" value="{{ old('code', $promotion->code) }}"></div>
        <div class="col-6"><label class="form-label">Тип</label><select name="type" class="form-select"><option value="percent" @selected(old('type',$promotion->type)=='percent')>Процент</option><option value="fixed" @selected(old('type',$promotion->type)=='fixed')>Фиксированная</option></select></div>
        <div class="col-6"><label class="form-label">Значение</label><input type="number" step="0.01" name="value" class="form-control" value="{{ old('value', $promotion->value) }}" required></div>
        <div class="col-6"><label class="form-label">Мин. сумма заказа</label><input type="number" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $promotion->min_order_amount) }}"></div>
        <div class="col-6"><label class="form-label">Бонус баллов</label><input type="number" name="bonus_points_reward" class="form-control" value="{{ old('bonus_points_reward', $promotion->bonus_points_reward) }}"></div>
        <div class="col-6"><label class="form-label">Начало</label><input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', $promotion->starts_at?->format('Y-m-d\TH:i')) }}"></div>
        <div class="col-6"><label class="form-label">Конец</label><input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', $promotion->ends_at?->format('Y-m-d\TH:i')) }}"></div>
        <div class="col-12 form-check"><input type="checkbox" name="auto_apply" value="1" class="form-check-input" @checked(old('auto_apply', $promotion->auto_apply))><label class="form-check-label">Применять автоматически</label></div>
        <div class="col-12 form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $promotion->is_active ?? true))><label class="form-check-label">Активна</label></div>
    </div>
    <button type="submit" class="btn-admin-primary mt-4"><i class="bi bi-check-lg"></i> Сохранить</button>
</form>
    </div>
</div>
@endsection
