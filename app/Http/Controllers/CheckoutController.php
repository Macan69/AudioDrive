<?php

namespace App\Http\Controllers;

use App\Services\BonusService;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout,
        private BonusService $bonus,
    ) {}

    public function index()
    {
        if ($this->cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $order = $this->checkout->createDraft();
        $this->checkout->syncItems($order);
        $order = $this->checkout->recalculateTotals($order);

        return redirect()->route('checkout.step', ['step' => 1]);
    }

    public function step(int $step)
    {
        $order = $this->checkout->getDraftOrder();
        if (! $order || $this->cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $order->load('items.product', 'promotion');
        $maxBonus = auth()->check()
            ? $this->bonus->calculateMaxUsable((float) $order->subtotal, auth()->user()->bonus_points)
            : 0;

        return view('checkout.step', compact('order', 'step', 'maxBonus'));
    }

    public function saveStep(Request $request, int $step)
    {
        $order = $this->checkout->getDraftOrder();
        if (! $order) {
            return redirect()->route('cart.index');
        }

        match ($step) {
            1 => $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:30',
            ]),
            2 => $request->validate([
                'shipping_city' => 'required|string|max:100',
                'shipping_address' => 'required|string|max:500',
                'shipping_method' => 'required|in:courier,pickup,post',
            ]),
            3 => $request->validate([
                'payment_method' => 'required|in:card,cash,online',
                'promo_code' => 'nullable|string|max:50',
                'bonus_used' => 'nullable|integer|min:0',
            ]),
            default => abort(404),
        };

        $data = $request->only([
            'customer_name', 'customer_email', 'customer_phone',
            'shipping_city', 'shipping_address', 'shipping_method',
            'payment_method', 'promo_code', 'comment',
        ]);

        if ($step === 3) {
            $bonusUsed = (int) $request->input('bonus_used', 0);
            if (auth()->check()) {
                $max = $this->bonus->calculateMaxUsable((float) $order->subtotal, auth()->user()->bonus_points);
                $bonusUsed = min($bonusUsed, $max);
            } else {
                $bonusUsed = 0;
            }
            $order->fill($data);
            $order->user_id = auth()->id();
            $order->checkout_step = 4;
            $order->save();
            $this->checkout->recalculateTotals($order, $request->promo_code, $bonusUsed);
        } else {
            $order->fill($data);
            $order->checkout_step = min($step + 1, 4);
            $order->save();
        }

        if ($step === 3) {
            return redirect()->route('checkout.step', ['step' => 4]);
        }

        return redirect()->route('checkout.step', ['step' => $step + 1]);
    }

    public function confirm()
    {
        $order = $this->checkout->getDraftOrder();
        if (! $order || $order->checkout_step < 4) {
            return redirect()->route('checkout.step', ['step' => 1]);
        }

        $order = $this->checkout->complete($order);

        return redirect()->route('checkout.success', $order)->with('success', 'Заказ оформлен!');
    }

    public function success($orderId)
    {
        $order = \App\Models\Order::with('items')->findOrFail($orderId);

        return view('checkout.success', compact('order'));
    }
}
