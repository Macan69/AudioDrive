@extends('layouts.app')
@section('title', 'О нас')
@section('main-class', 'main-about')

@section('content')
<div class="about-page">
    <section class="about-hero">
        <div class="about-hero__glow about-hero__glow--1" aria-hidden="true"></div>
        <div class="about-hero__glow about-hero__glow--2" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="about-hero__inner">
                <span class="about-hero__badge"><i class="bi bi-speaker-fill"></i> AudioDrive</span>
                <h1 class="about-hero__title">
                    Звук, которому<br>
                    <span class="about-hero__accent">можно доверять</span>
                </h1>
                <p class="about-hero__lead">
                    С 2015 года помогаем автолюбителям получить чистый, мощный и сбалансированный звук — от доступных решений до премиальной акустики.
                </p>
                <div class="about-hero__actions">
                    <a href="{{ route('catalog.index') }}" class="btn btn-brand btn-lg">
                        <i class="bi bi-grid"></i> Каталог
                    </a>
                    <a href="#contacts" class="btn btn-hero-outline btn-lg">
                        <i class="bi bi-chat-dots"></i> Связаться
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="container about-body">
        <section class="about-stats">
            <article class="about-stat">
                <span class="about-stat__icon about-stat__icon--years"><i class="bi bi-award-fill"></i></span>
                <h2 class="about-stat__title">С 2015 года</h2>
                <p>Более 11 лет подбираем и поставляем автомобильную акустику для тысяч клиентов по всей России.</p>
            </article>
            <article class="about-stat">
                <span class="about-stat__icon about-stat__icon--brands"><i class="bi bi-shield-check"></i></span>
                <h2 class="about-stat__title">Оригинальная продукция</h2>
                <p>Pioneer, Alpine, JBL, Focal, Kenwood, Hertz и другие официальные бренды.</p>
            </article>
            <article class="about-stat">
                <span class="about-stat__icon about-stat__icon--support"><i class="bi bi-headset"></i></span>
                <h2 class="about-stat__title">Поддержка 24/7</h2>
                <p>Консультируем по подбору, совместимости и настройке звука до и после покупки.</p>
            </article>
        </section>

        <section class="about-split">
            <div class="about-mission">
                <span class="about-section__label">О компании</span>
                <h2 class="about-section__title">Наша миссия</h2>
                <p>AudioDrive — специализированный интернет-магазин автомобильной акустики. Мы верим, что качественный звук делает каждую поездку комфортнее, а правильно подобранная система раскрывает потенциал вашего автомобиля.</p>
                <p>В каталоге — сабвуферы, усилители, динамики, головные устройства и аксессуары. Удобные фильтры по параметрам помогают быстро найти товар под ваши задачи и бюджет.</p>
            </div>
            <div class="about-benefits">
                <h3 class="about-benefits__title"><i class="bi bi-star-fill"></i> Почему выбирают нас</h3>
                <ul class="about-benefits__list">
                    <li><i class="bi bi-check-circle-fill"></i> Честные цены и регулярные акции</li>
                    <li><i class="bi bi-check-circle-fill"></i> Бонусная программа для постоянных клиентов</li>
                    <li><i class="bi bi-check-circle-fill"></i> Быстрая доставка по Москве и России</li>
                    <li><i class="bi bi-check-circle-fill"></i> Гарантия на всю продукцию</li>
                    <li><i class="bi bi-check-circle-fill"></i> Подбор комплекта «под ключ»</li>
                </ul>
            </div>
        </section>

        <section class="about-steps-section">
            <div class="about-steps-section__head">
                <span class="about-section__label">Процесс</span>
                <h2 class="about-section__title">Как мы работаем</h2>
            </div>
            <div class="about-steps">
                <article class="about-step">
                    <span class="about-step__num">1</span>
                    <h3>Выбираете товар</h3>
                    <p>В каталоге или с помощью консультанта</p>
                </article>
                <article class="about-step">
                    <span class="about-step__num">2</span>
                    <h3>Оформляете заказ</h3>
                    <p>Онлайн за несколько шагов</p>
                </article>
                <article class="about-step">
                    <span class="about-step__num">3</span>
                    <h3>Получаете доставку</h3>
                    <p>Курьером, почтой или самовывозом</p>
                </article>
                <article class="about-step">
                    <span class="about-step__num">4</span>
                    <h3>Наслаждаетесь звуком</h3>
                    <p>Устанавливаете и настраиваете систему</p>
                </article>
            </div>
        </section>

        <section class="about-showroom">
            <div class="about-showroom__content">
                <span class="about-section__label about-section__label--light">Шоурум</span>
                <h2 class="about-showroom__title">Приезжайте послушать вживую</h2>
                <p>Сравните динамики и сабвуферы, получите персональную рекомендацию. Предварительная запись не обязательна.</p>
                <div class="about-showroom__meta">
                    <span><i class="bi bi-geo-alt"></i> Москва, ул. Автозвука, 12</span>
                    <span><i class="bi bi-clock"></i> Пн–Вс, 10:00–20:00</span>
                </div>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn btn-brand btn-lg about-showroom__btn">
                <i class="bi bi-grid"></i> Перейти в каталог
            </a>
        </section>

        <section id="contacts" class="contacts-section">
            <div class="contacts-section__glow" aria-hidden="true"></div>
            <div class="contacts-section__inner">
                <header class="contacts-section__head">
                    <span class="contacts-section__label">Контакты</span>
                    <h2 class="contacts-section__title">Свяжитесь с AudioDrive</h2>
                    <p class="contacts-section__lead">Консультация, заказ, доставка и поддержка — ответим быстро и поможем с подбором акустики</p>
                </header>

                <div class="contacts-grid">
                    <a href="tel:+78005553535" class="contacts-card">
                        <span class="contacts-card__icon"><i class="bi bi-telephone-fill"></i></span>
                        <span class="contacts-card__label">Телефон</span>
                        <strong class="contacts-card__value">+7 (800) 555-35-35</strong>
                        <span class="contacts-card__hint">Бесплатно по России</span>
                    </a>
                    <a href="mailto:info@audiodrive.ru" class="contacts-card">
                        <span class="contacts-card__icon"><i class="bi bi-envelope-fill"></i></span>
                        <span class="contacts-card__label">Email</span>
                        <strong class="contacts-card__value">info@audiodrive.ru</strong>
                        <span class="contacts-card__hint">Ответ в течение дня</span>
                    </a>
                    <div class="contacts-card contacts-card--static">
                        <span class="contacts-card__icon"><i class="bi bi-geo-alt-fill"></i></span>
                        <span class="contacts-card__label">Шоурум</span>
                        <strong class="contacts-card__value">Москва, ул. Автозвука, 12</strong>
                        <span class="contacts-card__hint">м. Автозвуковая</span>
                    </div>
                    <div class="contacts-card contacts-card--static">
                        <span class="contacts-card__icon"><i class="bi bi-clock-fill"></i></span>
                        <span class="contacts-card__label">Режим работы</span>
                        <strong class="contacts-card__value">Пн–Вс, 10:00–20:00</strong>
                        <span class="contacts-card__hint">Без выходных</span>
                    </div>
                </div>

                <div class="contacts-bottom">
                    <div class="contacts-social">
                        <span class="contacts-social__title">Мы в соцсетях</span>
                        <div class="contacts-social__links">
                            <a href="https://vk.com/" target="_blank" rel="noopener noreferrer" class="contacts-social__link" title="ВКонтакте">
                                <img src="{{ cdn_asset('images/icons/vk.png') }}" width="36" height="36" alt="ВКонтакте">
                            </a>
                            <a href="https://max.ru/" target="_blank" rel="noopener noreferrer" class="contacts-social__link" title="MAX">
                                <img src="{{ cdn_asset('images/icons/max.svg') }}" width="36" height="36" alt="MAX">
                            </a>
                            <a href="mailto:info@audiodrive.ru" class="contacts-social__link contacts-social__link--mail" title="Email">
                                <i class="bi bi-envelope"></i>
                            </a>
                        </div>
                    </div>
                    <div class="contacts-actions">
                        <a href="{{ route('catalog.index') }}" class="btn btn-brand">
                            <i class="bi bi-grid"></i> Каталог
                        </a>
                        <a href="tel:+78005553535" class="btn contacts-btn-outline">
                            <i class="bi bi-telephone"></i> Позвонить
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
