@extends('layouts.admin')
@section('title', 'Дашборд')

@section('content')
<div class="admin-page-head">
    <div>
        <h1>Дашборд</h1>
        <p>Обзор магазина AudioDrive</p>
    </div>
</div>

<div class="admin-stats">
    <div class="admin-stat">
        <div class="admin-stat__icon admin-stat__icon--products"><i class="bi bi-box-seam"></i></div>
        <span class="admin-stat__label">Товары</span>
        <span class="admin-stat__value">{{ $stats['products'] }}</span>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon admin-stat__icon--orders"><i class="bi bi-bag-check"></i></div>
        <span class="admin-stat__label">Заказы</span>
        <span class="admin-stat__value">{{ $stats['orders'] }}</span>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon admin-stat__icon--users"><i class="bi bi-people"></i></div>
        <span class="admin-stat__label">Пользователи</span>
        <span class="admin-stat__value">{{ $stats['users'] }}</span>
    </div>
    <div class="admin-stat">
        <div class="admin-stat__icon admin-stat__icon--revenue"><i class="bi bi-currency-exchange"></i></div>
        <span class="admin-stat__label">Выручка</span>
        <span class="admin-stat__value">{{ number_format($stats['revenue'], 0, '', ' ') }} ₽</span>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__head">
        <h2><i class="bi bi-clock-history" style="color:var(--admin-brand)"></i> Последние заказы</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn-admin-ghost">Все заказы</a>
    </div>
    <div class="admin-card__body--flush">
        @if($recentOrders->isEmpty())
            <div class="admin-empty">Заказов пока нет</div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>№ заказа</th>
                            <th>Клиент</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td><span class="admin-table__name">{{ $order->number }}</span></td>
                                <td>{{ $order->customer_name ?? $order->user?->name ?? '—' }}</td>
                                <td>{{ number_format($order->total, 0, '', ' ') }} ₽</td>
                                <td>@include('admin.partials.status-badge', ['order' => $order])</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-admin-ghost">Открыть</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
