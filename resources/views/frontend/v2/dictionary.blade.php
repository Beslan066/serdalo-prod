@extends('frontend/layouts/default')


@section('content')

    <div class="container dictionary-container">
        <h3 class="dictionary-title">
            Словарь и клавиатурная раскладка ингушского языка
        </h3>

        <p class="dictionary-introduction">
            В процессе сохранения и развития ингушского языка, коллектив редакции общенациональной газеты «Сердало», разработал словарь и клавиатурную раскладку ингушского языка.Словарь предназначен для проверки правописания в Microsoft Word.
            Клавиатурная раскладка позволяет обычным образом переключать язык ввода и отказаться от суррогатов буквы Ӏ.
        </p>

        <video controls="controls" preload="none" class="dictionary-video">
            <source src="http://grishads.beget.tech/storage/qIl3qTtqtGOrMPheOA2kkQSwSUP7wAvRROt16mwm.mp4">
        </video>


        <div class="dictionary-support">
            <span>Чтобы повысить качество текстов на ингушском языке мы предлагаем вам установить словарь для проверки орфографии и клавиатурную раскладку ингушского языка.Скачать установочный файл можно по <a href="{{asset('inhdict-latest.exe')}}">ссылке</a>.</span>

            <span style="margin-top: 20px;">
                Нашли ошибку в словаре или отсутствует то или иное слово? <a href="">Напишите нам</a> ваше замечание.
                Если при установке возникнут ошибки звоните по номеру т. 8 928 698 54 69 Ибрагим. Будем рады помочь.
            </span>

        </div>
    </div>

    <div class="container">
        <div class="rubric-subscribe">
            <div class="subscribe-banner">
                <div class="container">
                    <div class="text">
                        <h2>{{ __('Подпишитесь на электронную газету') }}</h2>
                        <p>{{ __('(PDF) версия еженедельной общенациональной газеты «Сердало» и получайте свежие номера не выходя из дома!') }}</p>
                        <button><a href="{{route('payment-page')}}">{{ __('Подписаться') }}</a></button>
                    </div>
                    <img src="{{ asset('frontend/img/image 7.png') }}" alt="">
                </div>
            </div>
        </div>
    </div>

@endsection
