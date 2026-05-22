<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $orders = collect();
        $transactions = collect();

        if (! $user->isAdmin()) {
            $orders = $user->orders()->where('status', '!=', 'draft')->with('items')->latest()->paginate(10);
            $transactions = $user->bonusTransactions()->latest()->take(20)->get();
        }

        return view('account.index', compact('user', 'orders', 'transactions'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
        ]);

        auth()->user()->update($data);

        return back()->with('success', 'Профиль обновлён');
    }

    public function orderShow(int $id)
    {
        $order = auth()->user()->orders()->with('items')->findOrFail($id);

        return view('account.order', compact('order'));
    }
}
