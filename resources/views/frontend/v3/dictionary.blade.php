@extends('frontend/v3/layouts/default')
@section('content')
<div class="cm-single-page grid grid-cols-12 gap-5">
    <!-- main block -->
    <div class="col-span-12 sm:col-span-8 lg:col-span-9 xl:col-span-9">
        @include('frontend.v3.partials.page_title', [
            'title' => __('Словарь и клавиатурная раскладка ингушского языка'),
        ])
        <div class="cm-single-content container md:pr-10 lg:pr-20">
            <div class="cm-single-description text-sm sm:text-base">
                <p class="">
                    В процессе сохранения и развития ингушского языка, коллектив редакции общенациональной газеты «Сердало», разработал словарь и клавиатурную раскладку ингушского языка.
                    Словарь предназначен для проверки правописания в Microsoft Word.
                    Клавиатурная раскладка позволяет обычным образом переключать язык ввода и отказаться от суррогатов буквы Ӏ.
                </p>
                <div class="cm-content-media mb-5">
                    <video class="w-full" controls="controls" preload="none" poster="https://serdalo.ru/storage/AovV0I2EtW1KjOCmrbai62x5QncCU7w2NwqfTlcP.jpg">
                        <source src="http://serdalo.ru/storage/1G6nLBS9IDZqhxbPUt8WZTZwUUCvvO4UknaehaSf.mp4">
                    </video>
                </div>
                <p>
                    Чтобы повысить качество текстов на ингушском языке мы предлагаем вам установить словарь для проверки орфографии и
                    клавиатурную раскладку ингушского языка. <span style="color: #006633; font-weight: 600; font-size: 16px"><a href="{{asset('inhdict/inhdict-0.2.0.exe')}}">Скачать установочный файл</a>.</span>
                </p>
                <p>
                    Нашли ошибку в словаре или отсутствует то или иное слово? <a href="">Напишите нам</a> ваше замечание.
                    Если при установке возникнут ошибки звоните по номеру т. <a href="tel:8 928 698 54 69">8 928 698 54 69</a> Ибрагим. Будем рады помочь.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="py-5">
    @include('frontend.v3.partials.subscribe_lead_2')
</div>

@endsection
