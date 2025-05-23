@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message')
    <div>
        {{ __('Not Found') }}
        <br>
        <a href="{{ route('home') }}" style="text-decoration: underline; color: #007bff;">
            {{ __('Вернуться на главную') }}
        </a>
    </div>
@endsection
