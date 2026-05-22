<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct(private ImageUploadService $imageUpload) {}
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $attributes = ProductAttribute::orderBy('sort_order')->get();

        return view('admin.products.form', ['product' => new Product, 'categories' => $categories, 'attributes' => $attributes]);
    }

    public function store(Request $request)
    {
        $product = $this->saveProduct(new Product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Товар создан');
    }

    public function edit(Product $product)
    {
        $product->load('attributeValues');
        $categories = Category::all();
        $attributes = ProductAttribute::orderBy('sort_order')->get();

        return view('admin.products.form', compact('product', 'categories', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        $this->saveProduct($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Товар обновлён');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product);
        $product->delete();

        return back()->with('success', 'Товар удалён');
    }

    private function saveProduct(Product $product, Request $request): Product
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50',
            'brand' => 'required|string|max:100',
            'description' => 'required|string',
            'full_description' => 'nullable|string',
            'features_text' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'warranty_months' => 'nullable|integer|min:0|max:120',
            'country' => 'nullable|string|max:100',
            'weight' => 'nullable|string|max:50',
            'dimensions' => 'nullable|string|max:100',
            'package_contents' => 'nullable|string',
            'installation' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data['slug'] = $product->slug ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['features'] = collect(preg_split('/\r\n|\r|\n/', $request->input('features_text', '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
        unset($data['features_text']);
        $data['warranty_months'] = $data['warranty_months'] ?? 12;

        if ($request->hasFile('image')) {
            $this->deleteImage($product);
            $data['image'] = $this->imageUpload->storeProductImage($request->file('image'));
        } else {
            unset($data['image']);
        }

        $product->fill($data);
        $product->save();

        $product->attributeValues()->delete();
        foreach ($request->input('attributes', []) as $attrId => $value) {
            if ($value) {
                $product->attributeValues()->create([
                    'product_attribute_id' => $attrId,
                    'value' => $value,
                ]);
            }
        }

        return $product;
    }

    private function deleteImage(Product $product): void
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
    }
}
