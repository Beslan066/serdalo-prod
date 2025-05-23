@extends('frontend/v3/layouts/default')
@section('content')
<div class="">
    @include('frontend.v3.partials.page_title', [
        'title' => __('Подписка'),
    ])
    <div class="max-w-sm">
        <div class="mb-5">
            <a class="text-xl mr-5" href="{{ route('login-page') }}">{{ __('Войти') }}</a>
            <a class="text-xl font-semibold">{{ __('Зарегистрироваться') }}</a>
        </div>
        <form action="{{ route('register-frontend') }}" method="POST">
            @csrf
            <div class="flex flex-col mb-5 gap-5">
                <div class="">
                    <input name="name" type="text" class="py-2 px-4 w-full @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('Имя') }}">
                    @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <input name="last_name" type="text" class="py-2 px-4 w-full @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" placeholder="{{ __('Фамилия') }}">
                    @error('last_name')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <input name="email" type="email" class="py-2 px-4 w-full @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('Электронная почта') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <input name="password" type="password" class="py-2 px-4 w-full @error('password') is-invalid @enderror" value="{{ old('password') }}" placeholder="{{ __('Пароль') }}">
                    @error('password')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="">
                <div class="mb-5 items-center color-1 @error('personal_data') is-invalid @enderror">
                    <input class="cm-checkbox" name="personal_data" type="checkbox" value="1" @if(old('personal_data')) checked @endif id="personal-data-input">
                    <label class="color-2" for="personal-data-input">{{ __('Я принимаю правила регистрации и конфиденциальности и согласен (на) с политикой по защите персональных данных.') }}</label>
                    @error('personal_data')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <button class="cm-button active">{{ __('Зарегистрироваться') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection



