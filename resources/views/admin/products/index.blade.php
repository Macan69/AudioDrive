@extends('layouts.admin')
@section('title', 'Товары')

@section('content')
<div class="admin-page-head">
    <div>
        <h1>Товары</h1>
        <p>Управление каталогом и изображениями</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-admin-primary">
        <i class="bi bi-plus-lg"></i> Добавить товар
    </a>
</div>

<div class="admin-card">
    <div class="admin-card__body--flush">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Фото</th>
                        <th>Товар</th>
                        <th>Бренд</th>
                        <th>Категория</th>
                        <th>Цена</th>
                        <th>Склад</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>@include('partials.product-image', ['product' => $product, 'variant' => 'thumb', 'alt' => $product->name])</td>
                            <td>
                                <span class="admin-table__name">{{ $product->name }}</span>
                                <span class="admin-table__muted d-block">ID {{ $product->id }}</span>
                            </td>
                            <td>{{ $product->brand }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td><strong>{{ number_format($product->price, 0, '', ' ') }} ₽</strong></td>
                            <td>
                                @if($product->stock > 0)
                                    <span class="admin-badge admin-badge--success">{{ $product->stock }} шт.</span>
                                @else
                                    <span class="admin-badge admin-badge--danger">Нет</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-table__actions">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-admin-ghost">Изменить</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-admin-ghost is-danger" onclick="return confirm('Удалить товар?')">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="admin-empty">Товаров нет — добавьте первый</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($products->hasPages())
    <div class="admin-pagination">{{ $products->links() }}</div>
@endif
@endsection
