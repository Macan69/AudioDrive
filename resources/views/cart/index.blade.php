@extends('layouts.app')
@section('title', 'Корзина')

@section('content')
<div class="container">
    <h1 class="mb-4">Корзина</h1>
    @if($items->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <p class="mt-3">Корзина пуста</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-brand">Перейти в каталог</a>
        </div>
    @else
    <div class="table-responsive card border-0 shadow-sm">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Товар</th><th>Цена</th><th>Кол-во</th><th>Сумма</th><th></th></tr></thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <a href="{{ route('catalog.show', $item->product->slug) }}" class="text-decoration-none">{{ $item->product->name }}</a>
                        <br><small class="text-muted">{{ $item->product->brand }}</small>
                    </td>
                    <td>{{ number_format($item->product->price, 0, '', ' ') }} ₽</td>
                    <td>
                        <form action="{{ route('cart.update') }}" method="POST" class="d-flex gap-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="99" class="form-control form-control-sm" style="width:70px" onchange="this.form.submit()">
                        </form>
                    </td>
                    <td class="fw-bold">{{ number_format($item->subtotal, 0, '', ' ') }} ₽</td>
                    <td>
                        <form action="{{ route('cart.remove', $item->product->id) }}" method="POST">@csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h4>Итого: <span class="text-brand">{{ number_format($subtotal, 0, '', ' ') }} ₽</span></h4>
        <a href="{{ route('checkout.index') }}" class="btn btn-brand btn-lg">Оформить заказ</a>
    </div>
    @endif
</div>
@endsection
