@extends('layouts.app')
@section('title', 'Оформление заказа')

@section('content')
<div class="container" style="max-width:900px">
    <h1 class="mb-4">Оформление заказа</h1>

    <div class="checkout-steps d-flex justify-content-between mb-4 flex-wrap gap-2">
        @foreach([1=>'Контакты', 2=>'Доставка', 3=>'Оплата', 4=>'Подтверждение'] as $n => $label)
        <span class="step @if($step == $n) active @elseif($step > $n) done @endif">
            <i class="bi @if($step > $n) bi-check-circle-fill @else bi-circle @endif"></i> {{ $n }}. {{ $label }}
        </span>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            @if($step == 1)
            <form method="POST" action="{{ route('checkout.save', 1) }}">@csrf
                <div class="card border-0 shadow-sm"><div class="card-body">
                    <h5>Контактные данные</h5>
                    <div class="mb-3"><label class="form-label">ФИО</label><input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $order->customer_name ?? auth()->user()?->name) }}" required></div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $order->customer_email ?? auth()->user()?->email) }}" required></div>
                    <div class="mb-3"><label class="form-label">Телефон</label><input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $order->customer_phone ?? auth()->user()?->phone) }}" required></div>
                    <button type="submit" class="btn btn-brand">Далее →</button>
                </div></div>
            </form>
            @elseif($step == 2)
            <form method="POST" action="{{ route('checkout.save', 2) }}">@csrf
                <div class="card border-0 shadow-sm"><div class="card-body">
                    <h5>Доставка</h5>
                    <div class="mb-3"><label class="form-label">Город</label><input type="text" name="shipping_city" class="form-control" value="{{ old('shipping_city', $order->shipping_city) }}" required></div>
                    <div class="mb-3"><label class="form-label">Адрес</label><textarea name="shipping_address" class="form-control" rows="2" required>{{ old('shipping_address', $order->shipping_address) }}</textarea></div>
                    <div class="mb-3">
                        <label class="form-label">Способ доставки</label>
                        @foreach(['courier'=>'Курьер','pickup'=>'Самовывоз','post'=>'Почта'] as $v => $l)
                        <div class="form-check"><input class="form-check-input" type="radio" name="shipping_method" value="{{ $v }}" @checked(old('shipping_method', $order->shipping_method) == $v) required><label class="form-check-label">{{ $l }}</label></div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-brand">Далее →</button>
                </div></div>
            </form>
            @elseif($step == 3)
            <form method="POST" action="{{ route('checkout.save', 3) }}">@csrf
                <div class="card border-0 shadow-sm"><div class="card-body">
                    <h5>Оплата и скидки</h5>
                    <div class="mb-3">
                        @foreach(['card'=>'Карта','cash'=>'Наличные','online'=>'Онлайн'] as $v => $l)
                        <div class="form-check"><input class="form-check-input" type="radio" name="payment_method" value="{{ $v }}" @checked(old('payment_method', $order->payment_method) == $v) required><label class="form-check-label">{{ $l }}</label></div>
                        @endforeach
                    </div>
                    <div class="mb-3"><label class="form-label">Промокод</label><input type="text" name="promo_code" class="form-control" value="{{ old('promo_code', $order->promo_code) }}" placeholder="AUDIO500"></div>
                    @auth
                    <div class="mb-3">
                        <label class="form-label">Списать бонусов (доступно: {{ auth()->user()->bonus_points }}, макс: {{ $maxBonus }})</label>
                        <input type="number" name="bonus_used" class="form-control" min="0" max="{{ $maxBonus }}" value="{{ old('bonus_used', 0) }}">
                    </div>
                    @endauth
                    <div class="mb-3"><label class="form-label">Комментарий</label><textarea name="comment" class="form-control" rows="2">{{ old('comment', $order->comment) }}</textarea></div>
                    <button type="submit" class="btn btn-brand">Далее →</button>
                </div></div>
            </form>
            @elseif($step == 4)
            <div class="card border-0 shadow-sm"><div class="card-body">
                <h5>Подтверждение заказа</h5>
                <p><strong>Контакт:</strong> {{ $order->customer_name }}, {{ $order->customer_email }}, {{ $order->customer_phone }}</p>
                <p><strong>Доставка:</strong> {{ $order->shipping_city }}, {{ $order->shipping_address }} ({{ $order->shipping_method }})</p>
                <p><strong>Оплата:</strong> {{ $order->payment_method }}</p>
                @if($order->promo_code)<p><strong>Промокод:</strong> {{ $order->promo_code }}</p>@endif
                @if($order->bonus_used)<p><strong>Бонусов списано:</strong> {{ $order->bonus_used }}</p>@endif
                @if($order->bonus_earned)<p><strong>Бонусов начислится:</strong> {{ $order->bonus_earned }}</p>@endif
                <form method="POST" action="{{ route('checkout.confirm') }}">@csrf
                    <button type="submit" class="btn btn-brand btn-lg">Подтвердить заказ</button>
                </form>
            </div></div>
            @endif
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <h6>Ваш заказ</h6>
                @foreach($order->items as $item)
                <div class="d-flex justify-content-between small mb-1">
                    <span>{{ $item->product_name }} ×{{ $item->quantity }}</span>
                    <span>{{ number_format($item->total, 0, '', ' ') }} ₽</span>
                </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between"><span>Подытог</span><span>{{ number_format($order->subtotal, 0, '', ' ') }} ₽</span></div>
                @if($order->discount > 0)<div class="d-flex justify-content-between text-success"><span>Скидка</span><span>-{{ number_format($order->discount, 0, '', ' ') }} ₽</span></div>@endif
                <div class="d-flex justify-content-between fw-bold fs-5 mt-2"><span>Итого</span><span class="text-brand">{{ number_format($order->total, 0, '', ' ') }} ₽</span></div>
            </div></div>
        </div>
    </div>
</div>
@endsection
