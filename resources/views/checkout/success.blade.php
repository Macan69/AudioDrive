@extends('layouts.app')
@section('title', 'Заказ оформлен')

@section('content')
<div class="container text-center py-5">
    <i class="bi bi-check-circle-fill text-success display-1"></i>
    <h1 class="mt-3">Спасибо за заказ!</h1>
    <p class="lead">Номер заказа: <strong>{{ $order->number }}</strong></p>
    <p>Сумма: <strong class="text-brand">{{ number_format($order->total, 0, '', ' ') }} ₽</strong></p>
    @if($order->bonus_earned)<p>Начислено бонусов: <strong>{{ $order->bonus_earned }}</strong></p>@endif
    <a href="{{ route('account.index') }}" class="btn btn-brand me-2">Личный кабинет</a>
    <a href="{{ route('catalog.index') }}" class="btn btn-outline-dark">Продолжить покупки</a>
</div>
@endsection
