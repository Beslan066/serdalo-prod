<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('frontend/css/muzeum.css') }}?v={{env('APP_VERSION')}}">
    <link rel="stylesheet" href="{{ asset('frontend/css/custom.css') }}?v={{env('APP_VERSION')}}">
    <title>Музей</title>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo-section">
                <div class="logo">
                    <a href="{{route('home')}}">
                        <img src="{{ asset('frontend/img/logo.svg') }}" alt="">
                    </a>
                </div>
                <div class="logo-title">
                    <p>Общенациональная газета
                        Республики Ингушетия</p>
                </div>
            </div>
            <div class="menu-section">
                <div class="menu">
                    <ul>
                        <li class="nav-items"><a href="#">Новости</a></li>
                        <li class="nav-items"><a href="#">Статьи</a></li>
                        <li class="nav-items"><a href="#">Музей</a></li>
                    </ul>
                </div>
                <div class="lang">
                    <button data-tab="#tab_1" class="lang-btn ru-lang active">Рус</button>
                    <button data-tab="#tab_2" class="lang-btn ing-lang">Инг</button>
                </div>
                <div class="search-form">
                    <form>
                        <input type="text" placeholder="Поиск по сайту">
                        <button>Найти<img src="{{ asset('frontend/img/strelka.svg') }}" alt=""></button>
                    </form>
                </div>
                <div class="search">
                    <button class="search-btn" ></button>
                    <img class="burger-icon" src="{{ asset('frontend/img/burger-icon.svg') }}" alt="">
                </div>
            </div>
        </div>
        </div>
    </header>
    @yield('content')
    <footer>
        <div class="footer-social">
            <div class="container">
                <div class="links-document">
                    <a href="">Об издании</a>
                    <a href="">Правила использования материалов</a>
                    <a href="">Согласие на обработку персональных данных</a>
                </div>
                <div class="social-icons">
                    <div class="social-icon-item">
                        <img src="{{ asset('frontend/img/tg.svg') }}" alt="Телеграм Сердало">
                    </div>

                    <div class="social-icon-item">
                        <img src="{{ asset('frontend/img/vk.svg') }}" alt="Вконтакте Сердало">
                    </div>

                    <div class="social-icon-item">
                        <img src="{{ asset('frontend/img/dzen.svg') }}" alt=" Яндекс Дзен  Сердало">
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-info">
            <div class="container">
                <div class="contact-text">
                    <span class="redactor">Главный редактор: Курскиева Х.А. </span>
                    <span class="adress">386100, Республика Ингушетия, г. Назрань, пр. Базоркина, 60</span>

                    <a href="8 (8734) 77-10-85"  class="phone">8 (8734) 77-10-85</a>
                    <a href="info@serdalo.ru" class="mail">E-mail: info@serdalo.ru</a>
                </div>
                <div class="footer-logo">
                    <div><img src="img/12+.svg" alt=""></div>
                    <img src="{{ asset('frontend/img/footer-logo.svg') }}" alt="">
                </div>
            </div>
        </div>
        <div class="footer-desc">
            <div class="container">
                <p>Сетевое издание «Сердало» зарегистрировано Федеральной службой по надзору в сфере связи, информационных технологий и массовых коммуникаций (Роскомнадзор).</p>
                <p>Cвидетельство о регистрации СМИ: ЭЛ № ФС 77-78323 от 15.05.2020 г. Учредитель: Государственное автономное учреждение «Редакция общенациональной газеты «Сердало»</p>
            </div>
        </div>
    </footer>
    <script src="{{ asset('frontend/js/scripts.js') }}?v={{env('APP_VERSION')}}"></script>
</body>
</html>
