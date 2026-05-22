@extends('layouts.admin')
@section('title', 'Заказы')

@section('content')
<div class="admin-page-head">
    <div>
        <h1>Заказы</h1>
        <p>Все оформленные заказы клиентов</p>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card__body--flush">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Дата</th>
                        <th>Клиент</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><span class="admin-table__name">{{ $order->number }}</span></td>
                            <td>
                                <span>{{ $order->created_at->format('d.m.Y') }}</span>
                                <span class="admin-table__muted d-block">{{ $order->created_at->format('H:i') }}</span>
                            </td>
                            <td>{{ $order->customer_name ?? $order->user?->name ?? '—' }}</td>
                            <td><strong>{{ number_format($order->total, 0, '', ' ') }} ₽</strong></td>
                            <td>@include('admin.partials.status-badge', ['order' => $order])</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn-admin-ghost">Открыть</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="admin-empty">Заказов нет</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($orders->hasPages())
    <div class="admin-pagination">{{ $orders->links() }}</div>
@endif
@endsection
