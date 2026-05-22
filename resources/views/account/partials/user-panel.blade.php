<div class="account-tabs" role="tablist">
    <button class="account-tabs__btn active" type="button" data-bs-toggle="tab" data-bs-target="#account-orders" role="tab">
        <i class="bi bi-bag"></i> Заказы
        @if($orders->total() > 0)<span class="account-tabs__count">{{ $orders->total() }}</span>@endif
    </button>
    <button class="account-tabs__btn" type="button" data-bs-toggle="tab" data-bs-target="#account-bonus" role="tab">
        <i class="bi bi-gift"></i> Бонусы
    </button>
</div>

<div class="tab-content account-tab-content">
    <div class="tab-pane fade show active" id="account-orders" role="tabpanel">
        @forelse($orders as $order)
            <article class="account-order">
                <div class="account-order__main">
                    <div class="account-order__top">
                        <strong class="account-order__number">{{ $order->number }}</strong>
                        <span class="account-status account-status--{{ $order->statusTone() }}">{{ $order->statusLabel() }}</span>
                    </div>
                    <div class="account-order__meta">
                        <span><i class="bi bi-calendar3"></i> {{ $order->created_at->format('d.m.Y H:i') }}</span>
                        <span><i class="bi bi-box"></i> {{ $order->items->count() }} поз.</span>
                    </div>
                </div>
                <div class="account-order__side">
                    <div class="account-order__total">{{ number_format($order->total, 0, '', ' ') }} ₽</div>
                    <a href="{{ route('account.order', $order->id) }}" class="btn btn-outline-dark btn-sm">Подробнее</a>
                </div>
            </article>
        @empty
            <div class="account-empty">
                <i class="bi bi-bag-x"></i>
                <h3>Заказов пока нет</h3>
                <p>Оформите первый заказ в каталоге — он появится здесь.</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-brand">Перейти в каталог</a>
            </div>
        @endforelse

        @if($orders->hasPages())
            <div class="account-pagination">{{ $orders->links() }}</div>
        @endif
    </div>

    <div class="tab-pane fade" id="account-bonus" role="tabpanel">
        @if($transactions->isEmpty())
            <div class="account-empty account-empty--compact">
                <i class="bi bi-gift"></i>
                <h3>История бонусов пуста</h3>
                <p>Баллы появятся после регистрации и покупок.</p>
            </div>
        @else
            <div class="account-bonus-list">
                @foreach($transactions as $transaction)
                    <div class="account-bonus-item">
                        <div class="account-bonus-item__info">
                            <span class="account-bonus-item__desc">{{ $transaction->description }}</span>
                            <span class="account-bonus-item__date">{{ $transaction->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                        <span class="account-bonus-item__points {{ $transaction->points > 0 ? 'is-plus' : 'is-minus' }}">
                            {{ $transaction->points > 0 ? '+' : '' }}{{ $transaction->points }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
