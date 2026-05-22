<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'cart';

    public function items(): Collection
    {
        $cart = session(self::SESSION_KEY, []);
        $products = Product::with('category')
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        return collect($cart)->map(function ($qty, $id) use ($products) {
            $product = $products->get($id);
            if (! $product) {
                return null;
            }

            return (object) [
                'product' => $product,
                'quantity' => (int) $qty,
                'subtotal' => $product->price * $qty,
            ];
        })->filter()->values();
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $product = Product::active()->findOrFail($productId);
        $cart = session(self::SESSION_KEY, []);
        $current = $cart[$productId] ?? 0;
        $cart[$productId] = min($current + $quantity, $product->stock);
        session([self::SESSION_KEY => $cart]);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = session(self::SESSION_KEY, []);
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::active()->findOrFail($productId);
            $cart[$productId] = min($quantity, $product->stock);
        }
        session([self::SESSION_KEY => $cart]);
    }

    public function remove(int $productId): void
    {
        $cart = session(self::SESSION_KEY, []);
        unset($cart[$productId]);
        session([self::SESSION_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return (int) collect(session(self::SESSION_KEY, []))->sum();
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('subtotal');
    }

    public function isEmpty(): bool
    {
        return $this->items()->isEmpty();
    }
}
