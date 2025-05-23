@extends('frontend/v3/layouts/default')
@section('content')
<div class="">
    @include('frontend.v3.partials.page_title', [
        'title' => __('Подписка'),
    ])
    <div class="max-w-sm">
        <div class="mb-5">
            <a class="text-xl font-semibold mr-5">{{ __('Войти') }}</a>
            <a class="text-xl" href="{{ route('register-page') }}">{{ __('Зарегистрироваться') }}</a>
        </div>
        <form action="{{ route('login-frontend') }}" method="POST">
            @csrf
            <div class="flex flex-col mb-5 gap-5">
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
            <div class="flex justify-between items-center">
                <a class="color-7" @if(Route::has('password.request')) href="{{ route('password.request') }}" @endif>{{ __('Забыли пароль?') }}</a>
                <button class="cm-button active">{{ __('Войти') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
