@extends('layouts.admin')
@section('title', $product->exists ? 'Редактировать товар' : 'Новый товар')
@section('content')
<div class="admin-page-head">
    <div>
        <h1>{{ $product->exists ? 'Редактировать товар' : 'Новый товар' }}</h1>
        <p>{{ $product->exists ? $product->name : 'Заполните данные и загрузите фото' }}</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn-admin-ghost"><i class="bi bi-arrow-left"></i> К списку</a>
</div>

<div class="admin-card admin-form-card">
    <div class="admin-card__body">
<form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf @if($product->exists) @method('PUT') @endif
    <div class="row g-3">
        <div class="col-12">
            <label class="form-label">Фотография товара</label>
            @if($product->hasImage())
                <div class="mb-2" style="max-width:240px">
                    @include('partials.product-image', ['product' => $product, 'variant' => 'admin', 'alt' => $product->name])
                </div>
            @endif
            <input type="file" name="image" class="form-control" accept="image/jpeg,image/png,image/webp">
            <div class="form-text">JPG, PNG или WebP, до 2 МБ. Фото автоматически сжимается до 1200px.</div>
        </div>
        <div class="col-md-6"><label class="form-label">Название</label><input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required></div>
        <div class="col-md-3"><label class="form-label">Бренд</label><input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand) }}" required></div>
        <div class="col-md-3"><label class="form-label">Категория</label>
            <select name="category_id" class="form-select" required>
                @foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3"><label class="form-label">Артикул (SKU)</label><input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}"></div>
        <div class="col-12"><label class="form-label">Краткое описание</label><textarea name="description" class="form-control" rows="2" required>{{ old('description', $product->description) }}</textarea></div>
        <div class="col-12"><label class="form-label">Полное описание</label><textarea name="full_description" class="form-control" rows="4">{{ old('full_description', $product->full_description) }}</textarea></div>
        <div class="col-12"><label class="form-label">Преимущества (каждый пункт с новой строки)</label><textarea name="features_text" class="form-control" rows="4">{{ old('features_text', $product->features ? implode("\n", $product->features) : '') }}</textarea></div>
        <div class="col-md-3"><label class="form-label">Цена</label><input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required></div>
        <div class="col-md-3"><label class="form-label">Старая цена</label><input type="number" step="0.01" name="old_price" class="form-control" value="{{ old('old_price', $product->old_price) }}"></div>
        <div class="col-md-3"><label class="form-label">Склад</label><input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? 10) }}" required></div>
        <div class="col-md-3"><label class="form-label">Гарантия (мес.)</label><input type="number" name="warranty_months" class="form-control" value="{{ old('warranty_months', $product->warranty_months ?? 12) }}"></div>
        <div class="col-md-3"><label class="form-label">Страна</label><input type="text" name="country" class="form-control" value="{{ old('country', $product->country) }}"></div>
        <div class="col-md-3"><label class="form-label">Вес</label><input type="text" name="weight" class="form-control" value="{{ old('weight', $product->weight) }}" placeholder="8.2 кг"></div>
        <div class="col-md-3"><label class="form-label">Габариты</label><input type="text" name="dimensions" class="form-control" value="{{ old('dimensions', $product->dimensions) }}"></div>
        <div class="col-12"><label class="form-label">Комплектация</label><textarea name="package_contents" class="form-control" rows="3">{{ old('package_contents', $product->package_contents) }}</textarea></div>
        <div class="col-12"><label class="form-label">Установка</label><textarea name="installation" class="form-control" rows="2">{{ old('installation', $product->installation) }}</textarea></div>
        <div class="col-md-3 d-flex align-items-end gap-3">
            <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" @checked(old('is_active', $product->is_active ?? true))><label class="form-check-label">Активен</label></div>
            <div class="form-check"><input type="checkbox" name="is_featured" value="1" class="form-check-input" @checked(old('is_featured', $product->is_featured))><label class="form-check-label">Хит</label></div>
        </div>
        <div class="col-12"><h5>Характеристики</h5></div>
        @php $existing = $product->exists ? $product->attributeValues->keyBy('product_attribute_id') : collect(); @endphp
        @foreach($attributes as $attr)
        <div class="col-md-4">
            <label class="form-label">{{ $attr->name }}</label>
            <input type="text" name="attributes[{{ $attr->id }}]" class="form-control" value="{{ old("attributes.{$attr->id}", $existing->get($attr->id)?->value) }}">
        </div>
        @endforeach
    </div>
    <div class="d-flex flex-wrap gap-2 mt-4">
        <button type="submit" class="btn-admin-primary"><i class="bi bi-check-lg"></i> Сохранить</button>
        <a href="{{ route('admin.products.index') }}" class="btn-admin-ghost">Отмена</a>
    </div>
</form>
    </div>
</div>
@endsection
