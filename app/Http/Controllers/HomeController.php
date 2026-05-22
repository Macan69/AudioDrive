<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Product::active()->with(['category', 'attributeValues.attribute'])->where('is_featured', true)->take(8)->get();
        $categories = Category::withCount('products')->get();
        $promotions = Promotion::active()->where('auto_apply', true)->take(3)->get();

        return view('home', compact('featured', 'categories', 'promotions'));
    }

    public function about()
    {
        return view('about');
    }
}
