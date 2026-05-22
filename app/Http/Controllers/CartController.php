<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        return view('cart.index', ['items' => $this->cart->items(), 'subtotal' => $this->cart->subtotal()]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:99',
        ]);

        $this->cart->add((int) $request->product_id, (int) $request->input('quantity', 1));

        return back()->with('success', 'Товар добавлен в корзину');
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $this->cart->update((int) $request->product_id, (int) $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Корзина обновлена');
    }

    public function remove(int $productId)
    {
        $this->cart->remove($productId);

        return redirect()->route('cart.index')->with('success', 'Товар удалён из корзины');
    }
}
