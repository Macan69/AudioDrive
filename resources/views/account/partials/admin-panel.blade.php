<div class="account-card account-card--full">
    <div class="account-welcome account-welcome--admin">
        <div class="account-welcome__icon"><i class="bi bi-gear-wide-connected"></i></div>
        <div>
            <h2 class="account-welcome__title">Панель администратора</h2>
            <p class="account-welcome__text">Управляйте товарами, заказами, акциями и настройками магазина. Разделы заказов и бонусов в личном кабинете скрыты — всё доступно в админке.</p>
        </div>
    </div>

    <div class="account-admin-grid">
        <a href="{{ route('admin.dashboard') }}" class="account-admin-link">
            <i class="bi bi-speedometer2"></i>
            <span>Дашборд</span>
            <small>Статистика магазина</small>
        </a>
        <a href="{{ route('admin.products.index') }}" class="account-admin-link">
            <i class="bi bi-box-seam"></i>
            <span>Товары</span>
            <small>Каталог и фото</small>
        </a>
        <a href="{{ route('admin.orders.index') }}" class="account-admin-link">
            <i class="bi bi-bag-check"></i>
            <span>Заказы</span>
            <small>Обработка и статусы</small>
        </a>
        <a href="{{ route('admin.promotions.index') }}" class="account-admin-link">
            <i class="bi bi-percent"></i>
            <span>Акции</span>
            <small>Промокоды</small>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="account-admin-link">
            <i class="bi bi-tags"></i>
            <span>Категории</span>
            <small>Структура каталога</small>
        </a>
        <a href="{{ route('admin.bonus.index') }}" class="account-admin-link">
            <i class="bi bi-gift"></i>
            <span>Бонусы</span>
            <small>Настройки программы</small>
        </a>
    </div>
</div>
