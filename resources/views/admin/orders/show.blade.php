@extends('layouts.admin')
@section('title', 'Заказ '.$order->number)

@section('content')
<div class="admin-page-head">
    <div>
        <h1>Заказ {{ $order->number }}</h1>
        <p>{{ $order->created_at->format('d.m.Y в H:i') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn-admin-ghost"><i class="bi bi-arrow-left"></i> К заказам</a>
</div>

<div class="admin-order-meta">
    <div class="admin-order-meta__item">
        <span>Клиент</span>
        {{ $order->customer_name ?? '—' }}
    </div>
    <div class="admin-order-meta__item">
        <span>Email</span>
        {{ $order->customer_email ?? '—' }}
    </div>
    <div class="admin-order-meta__item">
        <span>Телефон</span>
        {{ $order->customer_phone ?? '—' }}
    </div>
    <div class="admin-order-meta__item">
        <span>Доставка</span>
        {{ $order->shipping_city }}{{ $order->shipping_address ? ', '.$order->shipping_address : '' }}
        @if($order->shipping_method)<br><small class="text-muted">{{ $order->shipping_method }}</small>@endif
    </div>
    <div class="admin-order-meta__item">
        <span>Статус</span>
        @include('admin.partials.status-badge', ['order' => $order])
    </div>
</div>

<div class="admin-card mb-3">
    <div class="admin-card__head"><h2>Состав заказа</h2></div>
    <div class="admin-card__body--flush">
        <div class="admin-table-wrap">
            <table class="admin-table">
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
                        <td colspan="3" class="text-end text-muted">Скидка</td>
                        <td class="text-end">−{{ number_format($order->discount, 0, '', ' ') }} ₽</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end text-muted">Списано бонусов</td>
                        <td class="text-end">{{ number_format($order->bonus_used, 0, '', ' ') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Итого</td>
                        <td class="text-end fw-bold" style="color:var(--admin-brand)">{{ number_format($order->total, 0, '', ' ') }} ₽</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.orders.status', $order) }}" class="admin-status-form">
    @csrf @method('PATCH')
    <label class="mb-0 fw-semibold">Изменить статус:</label>
    <select name="status" class="form-select">
        @foreach(\App\Models\Order::STATUSES as $value => $label)
            <option value="{{ $value }}" @selected($order->status == $value || ($order->status === 'pending' && $value === \App\Models\Order::STATUS_PLACED))>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-admin-primary">Сохранить</button>
</form>
@endsection
