@extends('frontend/layouts/default')
@section('content')
<div class="sign sign-in">
    <div class="container">
        <div class="sign-title">
            <h2>{{ __('Подписка') }}</h2>
        </div>
        <div class="form-section">
            <div class="sign-pages">
                <a href="{{ route('login-page') }}">{{ __('Войти') }}</a>
                <a style="font-weight: 600;">{{ __('Зарегистрироваться') }}</a>
            </div>
            <form action="{{ route('register-frontend') }}" method="POST">
                @csrf
                <input name="name" type="text" class="sign-input @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('Имя') }}">
                @error('name')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
                <input name="last_name" type="text" class="sign-input @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" placeholder="{{ __('Фамилия') }}">
                @error('last_name')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
                <input name="email" type="email" class="sign-input @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('Электронная почта') }}">
                @error('email')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
                <input name="password" type="password" class="sign-input @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="{{ __('Пароль') }}">
                @error('password')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
                <div class="sign-reg-btn">
                    <div class="checkbox @error('personal_data') is-invalid @enderror">
                        <input name="personal_data" type="checkbox" value="1" @if(old('personal_data')) checked @endif id="personal-data-input">
                        <label for="personal-data-input">{{ __('Я принимаю правила регистрации и конфиденциальности и согласен (на) с политикой по защите персональных данных.') }}</label>
                        @error('personal_data')
                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                    <button>{{ __('Войти') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection