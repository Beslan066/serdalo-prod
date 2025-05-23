@extends('frontend/layouts/default')
@section('content')
<div class="sign sign-in">
    <div class="container">
        <div class="sign-title">
            <h2>{{ __('Подписка') }}</h2>
        </div>
        <div class="form-section">
            <div class="sign-pages">
                <a style="font-weight: 600;">{{ __('Войти') }}</a>
                <a href="{{ route('register-page') }}">{{ __('Зарегистрироваться') }}</a>
            </div>
            <form action="{{ route('login-frontend') }}" method="POST">
                @csrf
                <input name="email" type="email" class="sign-input @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('Электронная почта') }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
                <input name="password" type="password" class="sign-input @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="{{ __('Пароль') }}">
                @error('password')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
                <div class="sign-btn">
                    <a @if(Route::has('password.request')) href="{{ route('password.request') }}" @endif>{{ __('Забыли пароль?') }}</a>
                    <button class="sign-btn-button">{{ __('Войти') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
