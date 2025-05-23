@extends('frontend.v3.layouts.default')

@push('styles')
    <link rel="stylesheet" href="{{asset('frontend/v3/assets/css/game.css')}}">
@endpush

@section('content')
    <div class="game-page">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <div id="main-word-container" class="mb-4">
                <span id="main-word" class="text-xl font-semibold">Загрузка...</span>
            </div>
            <input type="text" id="input-word" placeholder="Введите слово" class="border p-2 rounded w-full mb-4">
            <button id="submit-word"
                    class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700">Отправить</button>
            <div id="words-list" class="mt-4 flex"></div>
        </div>

        <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center relative">
                <span id="close-modal" class="absolute top-2 right-2 text-xl font-bold cursor-pointer">&times;</span>
                <p id="modal-message">Слово не найдено в базе. Попробуйте другое слово.</p>
            </div>
        </div>

        <div id="congrats-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center relative">
                <span id="close-congrats-modal"
                      class="absolute top-2 right-2 text-xl font-bold cursor-pointer">&times;</span>
                <p id="congrats-message">Поздравляем! Вы нашли все слова! Следующее слово начнется через 3 секунды.</p>
            </div>
        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
@endsection

@push('scripts')
    <link rel="stylesheet" href="{{asset('frontend/v3/assets/js/game.js')}}">
@endpush