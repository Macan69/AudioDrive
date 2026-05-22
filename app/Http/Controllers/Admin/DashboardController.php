<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::where('status', '!=', 'draft')->count(),
            'users' => User::count(),
            'revenue' => Order::where('status', '!=', 'draft')->sum('total'),
        ];
        $recentOrders = Order::with('user')->where('status', '!=', 'draft')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
