<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Общенациональная газета республики</title>
    <style>
        
    </style>
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
                    <p>{{ __('Общенациональная газета') }}<br>
                        {{ __('Республики Ингушетия') }}</p>
                </div>
            </div>
            <div class="menu-section">
                <div class="menu">
                    <ul>
                        <li class="nav-items"><a href="{{ route('posts-index') }}">{{ __('Новости') }}</a></li>
                        <li class="nav-items"><a href="{{ route('materials-index') }}">{{ __('Статьи') }}</a></li>
                        <!--<li class="nav-items"><a href="{{route('museum')}}">{{ __('Музей') }}</a></li>-->
                        <!--<li class="nav-items"><a href="{{route('payment-page')}}">{{ __('Подписка') }}</a></li>-->
                        @guest
                            <!--<li class="nav-item">
                                <a href="{{ route('login-page') }}">{{ __('Вход') }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('register-page') }}">{{ __('Регистрация') }}</a>
                            </li>-->
                        @else
                            <li class="nav-item">
                                <a href="#">{{ Auth::user()->name }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Выйти') }}</a>
                                <form id="logout-form" action="{{ route('logout-frontend') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
                <div class="lang">
                    <a href="@if(App::getLocale() !== 'ru') {{ route('change-language', 'ru') }} @else # @endif" style="color: unset;text-decoration: none;">
                        <button data-tab="#tab_1" class="lang-btn ru-lang @if(App::getLocale() === 'ru') active @endif">{{ __('Рус') }}</button>
                    </a>
                    <a href="@if(App::getLocale() !== 'inh') {{ route('change-language', 'inh') }} @else # @endif" style="color: unset;text-decoration: none;">
                        <button data-tab="#tab_2" class="lang-btn ing-lang @if(App::getLocale() === 'inh') active @endif">{{ __('Инг') }}</button>
                    </a>
                </div>
                <div class="search-form">
                  <form method="get" action="{{ route('search-index') }}">
                        <input type="text" name="search" placeholder="{{ __('Поиск по сайту') }}">
                        <button>{{ __('Найти') }}<img src="{{ asset('frontend/img/strelka.svg') }}" alt=""></button>
                    </form>
                </div>
                <div class="search">
                    <button class="search-btn" ></button>
                    <img class="burger-icon" src="{{ asset('frontend/img/burger-icon.svg') }}" alt="">
                </div>
            </div>
        </div>
    </header>

    <div class="rubric-menu">
        <div class="container">

            <div class="rubrics">

                <div class="rubric-close close-btn-rubric">
                    <form method="get" action="{{ route('search-index') }}">
                        <input type="text" name="search" placeholder="{{ __('Поиск по сайту') }}">
                        <button>{{ __('Найти') }}<img src="{{ asset('frontend/img/strelka.svg') }}" alt=""></button>
                    </form>
                    <button class="rubric-close-btn"><img src="{{asset('frontend/img/burger-close.svg') }}" alt=""></button>
                </div>

                <ul>
                    @foreach($categories ?? [] as $category)
                        <li><a href="{{ route('materials-index', $category->id) }}">{{$category->title}}</a></li>
                    @endforeach
                </ul>

                <div class="burger-menu">

                    <div class="menu-services">
                        <span><a>{{ __('Видеостудия Сердало') }}</a></span>
                        <span><a>{{ __('Литературный салон') }}</a></span>
                        <span><a>{{ __('Музей') }}</a></span>
                        <span><a href="{{route('dictionary')}}">{{ __('Словарь') }}</a></span>
                    </div>

                    <div class="newspaper-menu">
                        <span><a href="{{route('archive-index')}}">{{ __('Архив газеты') }}</a></span>
                        <span><a href="{{route('payment-page')}}">{{ __('Подписка') }}</a></span>
                    </div>

                    <div class="menu-footer">
                        <span><a>{{ __('Об издании') }}</a></span>
                        <span><a>{{ __('Регистрация') }}</a></span>
                        <span><a>{{ __('Контакты') }}</a></span>
                    </div>
                </div>
            </div>


        </div>
    </div>
    @if (session('status'))
        <div style="color: green;">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <ul style="color: red;">
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    @endif
    @yield('content')
    <footer>
        <div class="footer-social">
            <div class="container">
                <div class="links-document">
                    <a href="">{{ __('Об издании') }}</a>
                    <a href="">{{ __('Правила использования материалов') }}</a>
                    <a href="{{route('personal')}}">{{ __('Согласие на обработку персональных данных') }}</a>
                </div>
                <div class="social-icons">
                    <div class="social-icon-item">
                        <a href="https://t.me/gserdalo" target="_blank">
                            <img src="{{ asset('frontend/img/tg.svg') }}" alt="{{ __('Телеграм Сердало') }}">
                        </a>
                    </div>
                    <div class="social-icon-item">
                        <a href="https://vk.com/gserdalo" target="_blank">
                            <img src="{{ asset('frontend/img/vk.svg') }}" alt="{{ __('Вконтакте Сердало') }}">
                        </a>
                    </div>
                    <div class="social-icon-item">
                        <a href="https://dzen.ru/id/61769dfd86f2571800496487" target="_blank">
                            <img src="{{ asset('frontend/img/dzen.svg') }}" alt=" {{ __('Яндекс Дзен Сердало') }}">
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-info">
            <div class="container">
                <div class="contact-text">
                    <span class="redactor">{{ __('Главный редактор: Курскиева Х.А.') }} </span>
                    <span class="adress">{{ __('386100, Республика Ингушетия, г. Назрань, пр. Базоркина, 60') }}</span>

                    <a href="8 (8734) 77-10-85"  class="phone">8 (8734) 77-10-85</a>
                    <a href="info@serdalo.ru" class="mail">E-mail: info@serdalo.ru</a>
                </div>
                <div class="footer-logo">
                    <div><img src="{{ asset('frontend/img/12+.svg') }}" alt=""></div>
                    <img src="{{ asset('frontend/img/footer-logo.svg') }}" alt="">
                </div>

                <div class="second-social-icons">
                    <div class="social-icons social-mobile">
                        <div class="social-icon-item">
                            <a href="https://t.me/gserdalo" target="_blank">
                                <img src="{{ asset('frontend/img/tg.svg') }}" alt="{{ __('Телеграм Сердало') }}">
                            </a>
                        </div>
                        <div class="social-icon-item">
                            <a href="https://vk.com/gserdalo" target="_blank">
                                <img src="{{ asset('frontend/img/vk.svg') }}" alt="{{ __('Вконтакте Сердало') }}">
                            </a>
                        </div>
                        <div class="social-icon-item">
                            <a href="https://dzen.ru/id/61769dfd86f2571800496487" target="_blank">
                                <img src="{{ asset('frontend/img/dzen.svg') }}" alt=" {{ __('Яндекс Дзен Сердало') }}">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-desc">
            <div class="container">
                <p>{{ __('Сетевое издание «Сердало» зарегистрировано Федеральной службой по надзору в сфере связи, информационных технологий и массовых коммуникаций (Роскомнадзор).') }}</p>
                <p>{{ __('Cвидетельство о регистрации СМИ: ЭЛ № ФС 77-78323 от 15.05.2020 г. Учредитель: Государственное автономное учреждение «Редакция общенациональной газеты «Сердало»') }}</p>
            </div>
        </div>
    </footer>
    <script src="{{ asset('frontend/js/scripts.js') }}?v={{env('APP_VERSION')}}"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $('.bg-video-icon-container').on('click', function(e){
            e.preventDefault();
            if(!$(this).data('video-path')) {
                return;
            }
            if($(this).hasClass('bg-video--show-video')) {
                return;
            }
            $(this).addClass('bg-video--show-video');
            $(this).append('<video autoplay="autoplay" controls="controls"><source src="' + $(this).data('video-path') + '" /></video>');
        });
        $(document).on('click', function(e){
            if( $('.rubric-menu').css('display') == 'block' && $(e.target).closest('.search .burger-icon').length == 0 && $(e.target).closest('.rubric-menu .rubrics').length == 0 ) {
                $('.rubric-menu').css({'display': 'none'});
            }
        });
    });
    </script>
</body>
</html>
