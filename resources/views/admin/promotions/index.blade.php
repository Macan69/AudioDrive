@extends('layouts.admin')
@section('title', 'Акции')

@section('content')
<div class="admin-page-head">
    <div>
        <h1>Акции</h1>
        <p>Промокоды и автоматические скидки</p>
    </div>
    <a href="{{ route('admin.promotions.create') }}" class="btn-admin-primary">
        <i class="bi bi-plus-lg"></i> Добавить акцию
    </a>
</div>

<div class="admin-card">
    <div class="admin-card__body--flush">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Код</th>
                        <th>Тип</th>
                        <th>Значение</th>
                        <th>Авто</th>
                        <th>Статус</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promotion)
                        <tr>
                            <td><span class="admin-table__name">{{ $promotion->name }}</span></td>
                            <td>@if($promotion->code)<code>{{ $promotion->code }}</code>@else—@endif</td>
                            <td>{{ $promotion->type === 'percent' ? 'Процент' : 'Фикс.' }}</td>
                            <td>{{ $promotion->value }}{{ $promotion->type === 'percent' ? '%' : ' ₽' }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $promotion->auto_apply ? 'yes' : 'no' }}">
                                    {{ $promotion->auto_apply ? 'Да' : 'Нет' }}
                                </span>
                            </td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $promotion->is_active ? 'success' : 'muted' }}">
                                    {{ $promotion->is_active ? 'Активна' : 'Выкл.' }}
                                </span>
                            </td>
                            <td>
                                <div class="admin-table__actions">
                                    <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn-admin-ghost">Изменить</a>
                                    <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-admin-ghost is-danger" onclick="return confirm('Удалить акцию?')">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><div class="admin-empty">Акций нет</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($promotions->hasPages())
    <div class="admin-pagination">{{ $promotions->links() }}</div>
@endif
@endsection
