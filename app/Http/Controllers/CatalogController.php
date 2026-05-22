<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['category', 'attributeValues.attribute']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('brand')) {
            $query->whereIn('brand', (array) $request->brand);
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        foreach ($request->input('attr', []) as $slug => $values) {
            if (empty($values)) {
                continue;
            }
            $values = (array) $values;
            $query->whereHas('attributeValues', function ($q) use ($slug, $values) {
                $q->whereHas('attribute', fn ($a) => $a->where('slug', $slug))
                    ->whereIn('value', $values);
            });
        }

        $sort = $request->get('sort', 'newest');
        $query = match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'name' => $query->orderBy('name'),
            default => $query->orderByDesc('created_at'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::withCount('products')->get();
        $brands = Product::active()->distinct()->orderBy('brand')->pluck('brand');
        $filterAttributes = ProductAttribute::where('is_filterable', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($attr) {
                $attr->filter_values = \App\Models\ProductAttributeValue::where('product_attribute_id', $attr->id)
                    ->distinct()
                    ->orderBy('value')
                    ->pluck('value');

                return $attr;
            });

        return view('catalog.index', compact('products', 'categories', 'brands', 'filterAttributes'));
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['category', 'attributeValues.attribute'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Product::active()
            ->with(['category', 'attributeValues.attribute'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'related'));
    }
}
