@extends('layouts.app')
@section('title', 'Заказ '.$order->number)

@section('content')
<div class="account-page">
    <div class="container container--narrow">
        <nav class="account-breadcrumb">
            <a href="{{ route('account.index') }}"><i class="bi bi-arrow-left"></i> Личный кабинет</a>
        </nav>

        <header class="account-order-header">
            <div>
                <span class="account-header__label">Заказ</span>
                <h1 class="account-order-header__title">{{ $order->number }}</h1>
                <p class="account-order-header__date">
                    <i class="bi bi-calendar3"></i> {{ $order->created_at->format('d.m.Y в H:i') }}
                </p>
            </div>
            <span class="account-status account-status--lg account-status--{{ $order->statusTone() }}">{{ $order->statusLabel() }}</span>
        </header>

        <div class="account-order-summary">
            <div class="account-order-stat">
                <span class="account-order-stat__label">Сумма заказа</span>
                <strong class="account-order-stat__value">{{ number_format($order->total, 0, '', ' ') }} ₽</strong>
            </div>
            @if($order->discount > 0)
                <div class="account-order-stat">
                    <span class="account-order-stat__label">Скидка</span>
                    <strong class="account-order-stat__value text-success">−{{ number_format($order->discount, 0, '', ' ') }} ₽</strong>
                </div>
            @endif
            @if($order->bonus_used > 0)
                <div class="account-order-stat">
                    <span class="account-order-stat__label">Списано бонусов</span>
                    <strong class="account-order-stat__value">{{ number_format($order->bonus_used, 0, '', ' ') }}</strong>
                </div>
            @endif
            @if($order->bonus_earned > 0)
                <div class="account-order-stat">
                    <span class="account-order-stat__label">Начислено бонусов</span>
                    <strong class="account-order-stat__value text-brand">+{{ number_format($order->bonus_earned, 0, '', ' ') }}</strong>
                </div>
            @endif
        </div>

        @if($order->shipping_city || $order->shipping_address)
            <div class="account-card account-card--inline">
                <h3 class="account-card__title"><i class="bi bi-truck"></i> Доставка</h3>
                <p class="mb-0 text-muted">
                    {{ $order->shipping_city }}{{ $order->shipping_address ? ', '.$order->shipping_address : '' }}
                    @if($order->shipping_method) · {{ $order->shipping_method }} @endif
                </p>
            </div>
        @endif

        <div class="account-card account-card--full">
            <h3 class="account-card__title"><i class="bi bi-list-ul"></i> Состав заказа</h3>
            <div class="table-responsive">
                <table class="account-table">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th class="text-center">Кол-во</th>
                            <th class="text-end">Цена</th>
                            <th class="text-end">Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">{{ number_format($item->price, 0, '', ' ') }} ₽</td>
                                <td class="text-end fw-semibold">{{ number_format($item->total, 0, '', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end">Итого</td>
                            <td class="text-end account-table__total">{{ number_format($order->total, 0, '', ' ') }} ₽</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
