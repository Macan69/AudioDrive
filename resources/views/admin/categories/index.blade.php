@extends('layouts.admin')
@section('title', 'Категории')

@section('content')
<div class="admin-page-head">
    <div>
        <h1>Категории</h1>
        <p>Структура каталога товаров</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.categories.store') }}" class="admin-inline-form">
    @csrf
    <div class="flex-grow-1" style="min-width:200px">
        <label class="form-label">Новая категория</label>
        <input type="text" name="name" class="form-control" placeholder="Название категории" required>
    </div>
    <button type="submit" class="btn-admin-primary">Добавить</button>
</form>

<div class="admin-card">
    <div class="admin-card__body--flush">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Slug</th>
                        <th>Товаров</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td><span class="admin-table__name">{{ $category->name }}</span></td>
                            <td><code class="small">{{ $category->slug }}</code></td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-admin-ghost is-danger" onclick="return confirm('Удалить категорию?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4"><div class="admin-empty">Категорий нет</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
