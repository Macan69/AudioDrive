<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(
        private CartService $cart,
        private PromotionService $promotions,
        private BonusService $bonus,
    ) {}

    public function getDraftOrder(): ?Order
    {
        $orderId = session('checkout_order_id');

        return $orderId ? Order::where('status', 'draft')->find($orderId) : null;
    }

    public function createDraft(): Order
    {
        $existing = $this->getDraftOrder();
        if ($existing) {
            return $existing;
        }

        $order = Order::create([
            'number' => Order::generateNumber(),
            'user_id' => auth()->id(),
            'status' => 'draft',
            'checkout_step' => 1,
            'subtotal' => $this->cart->subtotal(),
        ]);

        session(['checkout_order_id' => $order->id]);

        return $order;
    }

    public function syncItems(Order $order): void
    {
        $order->items()->delete();
        $subtotal = 0;

        foreach ($this->cart->items() as $item) {
            $lineTotal = $item->product->price * $item->quantity;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'product_name' => $item->product->name,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'total' => $lineTotal,
            ]);
            $subtotal += $lineTotal;
        }

        $order->update(['subtotal' => $subtotal]);
    }

    public function recalculateTotals(Order $order, ?string $promoCode = null, int $bonusUsed = 0): Order
    {
        $promo = $this->promotions->resolvePromotion($promoCode ?? $order->promo_code, (float) $order->subtotal);
        $discount = $promo ? $this->promotions->calculateDiscount($promo, (float) $order->subtotal) : 0;
        $bonusDiscount = $this->bonus->bonusToMoney($bonusUsed);
        $afterDiscount = max(0, (float) $order->subtotal - $discount - $bonusDiscount);
        $bonusEarned = $this->bonus->calculateEarned(
            $afterDiscount,
            $promo?->bonus_points_reward ?? 0
        );

        $order->update([
            'discount' => $discount + $bonusDiscount,
            'bonus_used' => $bonusUsed,
            'bonus_earned' => $bonusEarned,
            'total' => $afterDiscount,
            'promo_code' => $promo?->code,
            'promotion_id' => $promo?->id,
        ]);

        return $order->fresh();
    }

    public function complete(Order $order): Order
    {
        return DB::transaction(function () use ($order) {
            $order->update(['status' => Order::STATUS_PLACED, 'checkout_step' => 4]);

            if ($user = $order->user) {
                $this->bonus->applyToOrder($order, $user);
            }

            $this->cart->clear();
            session()->forget('checkout_order_id');

            return $order;
        });
    }
}
