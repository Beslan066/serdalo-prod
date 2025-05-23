@extends('frontend.v3.layouts.default')

@section('content')
    <div class="games-head">
        <div class="flex justify-center flex-col w-full mb-4">
            <h3>Игровой раздел "Сердало"</h3>
        </div>
    </div>
    <div class="games-page">
        <a href="{{route('game')}}">
            <div class="game-card ">
                <div class="loader">
                    <span>С</span>
                    <span>л</span>
                    <span>0</span>
                    <span>в</span>
                    <span>а</span>
                    <span> </span>
                    <span>и</span>
                    <span>з</span>
                    <span> </span>
                    <span>с</span>
                    <span>л</span>
                    <span>о</span>
                    <span>в</span>
                    <span>а</span>
                </div>
            </div>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
@endsection
