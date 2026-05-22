<footer class="site-footer mt-4 mt-md-5 py-3 py-md-5">
    <div class="container">
        <div class="row g-2 g-md-4">
            <div class="col-12 col-lg-4 col-md-6">
                <h5 class="text-white mb-2 mb-md-3 fs-6 fs-md-5"><i class="bi bi-speaker-fill text-brand"></i> AudioDrive</h5>
                <p class="small d-none d-md-block mb-0">Интернет-магазин автомобильной акустики премиум-класса. Сабвуферы, усилители, динамики и головные устройства от ведущих брендов.</p>
                <div class="d-flex align-items-center gap-2 mt-2 mt-md-3 footer-socials">
                    <a href="https://vk.com/" target="_blank" rel="noopener noreferrer" class="footer-social-brand" title="ВКонтакте" aria-label="ВКонтакте">
                        <img src="{{ asset('images/icons/vk.png') }}" width="40" height="40" alt="ВКонтакте">
                    </a>
                    <a href="https://max.ru/" target="_blank" rel="noopener noreferrer" class="footer-social-brand" title="Мессенджер MAX" aria-label="Мессенджер MAX">
                        <img src="{{ asset('images/icons/max.svg') }}" width="40" height="40" alt="MAX">
                    </a>
                    <a href="mailto:info@audiodrive.ru" class="footer-social" title="Email" aria-label="Email">
                        <i class="bi bi-envelope"></i>
                    </a>
                </div>
            </div>
            <div class="col-6 col-lg-2 col-md-6">
                <h6 class="text-white footer-heading">Каталог</h6>
                <ul class="list-unstyled small footer-links mb-0">
                    <li><a href="{{ route('catalog.index') }}">Все товары</a></li>
                    <li><a href="{{ route('catalog.index', ['category' => 'subwoofers']) }}">Сабвуферы</a></li>
                    <li><a href="{{ route('catalog.index', ['category' => 'amplifiers']) }}">Усилители</a></li>
                    <li><a href="{{ route('catalog.index', ['category' => 'speakers']) }}">Динамики</a></li>
                    <li><a href="{{ route('catalog.index', ['category' => 'head-units']) }}">Магнитолы</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <h6 class="text-white footer-heading">Покупателям</h6>
                <ul class="list-unstyled small footer-links mb-0">
                    <li><a href="{{ route('cart.index') }}">Корзина</a></li>
                    <li><a href="{{ route('account.index') }}">Личный кабинет</a></li>
                    <li><a href="{{ route('register') }}">Бонусы</a></li>
                    <li><a href="{{ route('about') }}">О нас</a></li>
                    <li><a href="{{ route('about') }}#contacts">Контакты</a></li>
                </ul>
            </div>
            <div class="col-12 col-lg-3 col-md-6">
                <h6 class="text-white footer-heading">Преимущества</h6>
                <ul class="list-unstyled small footer-links footer-benefits mb-2 mb-md-3">
                    <li><i class="bi bi-truck text-brand"></i> Доставка по России</li>
                    <li><i class="bi bi-shield-check text-brand"></i> Гарантия на товары</li>
                    <li class="d-none d-sm-block"><i class="bi bi-credit-card text-brand"></i> Оплата картой и онлайн</li>
                    <li class="d-none d-sm-block"><i class="bi bi-arrow-repeat text-brand"></i> Возврат 14 дней</li>
                </ul>
                <p class="small mb-1 footer-contact"><i class="bi bi-telephone text-brand"></i> <a href="tel:+78005553535">+7 (800) 555-35-35</a></p>
                <p class="small mb-0 footer-contact"><i class="bi bi-clock text-brand"></i> Пн–Вс, 10:00–20:00</p>
            </div>
        </div>

        <div class="row g-2 mt-2 pt-2 pt-md-4 border-top border-secondary footer-bottom">
            <div class="col-md-6 d-none d-md-block">
                <div class="d-flex flex-wrap gap-3 small text-secondary">
                    <span><i class="bi bi-credit-card-2-front"></i> Visa</span>
                    <span><i class="bi bi-credit-card"></i> Mastercard</span>
                    <span><i class="bi bi-wallet2"></i> СБП</span>
                    <span><i class="bi bi-cash-coin"></i> Наличные</span>
                </div>
            </div>
            <div class="col-md-6 text-md-end small d-none d-md-block">
                <a href="{{ route('catalog.index') }}" class="me-3">Каталог</a>
                <a href="{{ route('about') }}" class="me-3">О компании</a>
                <a href="{{ route('about') }}#contacts">Связаться</a>
            </div>
        </div>

        <p class="text-center mb-0 mt-2 small text-secondary footer-copy">&copy; {{ date('Y') }} AudioDrive</p>
    </div>
</footer>
