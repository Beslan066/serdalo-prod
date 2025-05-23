@extends('frontend/v3/layouts/default')
@section('content')
<div class="">
    @include('frontend.v3.partials.page_title', [
        'title' => __('Подписка на газету в электронном формате'),
    ])
    @if($active_subscribe)
        <div class="color-7" style="color: green;">
            <h3>{{ __('Ваша подписка активна с') }} {{ $active_subscribe->subrscribe_start_at->format('d.m.Y') }} {{ __('до') }} {{ $active_subscribe->subrscribe_end_at->format('d.m.Y') }}</h3>
        </div>
    @elseif($next_active_subscribe)
        <div class="color-5" style="color: blue;">
            <h3>{{ __('Ваша подписка будет активна с') }} {{ $next_active_subscribe->subrscribe_start_at->format('d.m.Y') }} {{ __('до') }} {{ $next_active_subscribe->subrscribe_end_at->format('d.m.Y') }}</h3>
        </div>
    @endif
    @if(!$next_active_subscribe && !$active_subscribe)
        @if(request()->has('paySuccess'))
            <div class="text-warning" style="color: orange;">
                <h3>{{ __('Ваша подписка еще не оплачена, попробуйте обновить страницу, или обратитесь к администратору') }}</h3>
            </div>
        @endif
        <form class="" action="https://yoomoney.ru/quickpay/confirm.xml" method="POST" accept-charset="UTF-8">
            <fieldset class="mb-5">
                @foreach($tariffs ?? [] as $tariff) 
                    <p><label><input type="radio" name="sum" value="{{ $tariff['amount'] }}" @if($loop->first) checked="" @endif/>{{ $tariff['amount'] }} руб. - {{ $tariff['label'] }}</label></p>
                @endforeach
            </fieldset>
            <fieldset class="mb-5">
                <p><label><input type="radio" name="paymentType" value="PC" checked=""/>{{ __('ЮMoney') }}</label></p>
                <p><label><input type="radio" name="paymentType" value="AC"/>{{ __('Банковской картой') }}</label></p>
            </fieldset>
            <input type="hidden" name="receiver" value="410013252120892">
            <input type="hidden" name="formcomment" value="{{ __('Подписка на период') }} {{ $tariff[0]['label'] ?? '' }}">
            <input type="hidden" name="short-dest" value="{{ __('Подписка на период') }} {{ $tariff[0]['label'] ?? '' }}">
            <input type="hidden" name="label" value="{{ auth()->id() }}">
            <input type="hidden" name="quickpay-form" value="shop">
            <input type="hidden" name="targets" value="{{ __('Подписка на период') }} {{ $tariff[0]['label'] ?? '' }}">
            <input type="hidden" name="successURL" value="http://grishads.beget.tech/payment?paySuccess=true">
            <div class="mb-5">
                <p>{{ __('Оформив подписку до 10 числа (включительно) текущего месяца, вы получаете доступ к номерам газеты за текущий месяц и на все последующие, которые вы оплатили. Если же подписка оформлена после 10 числа текущего месяца, доступ будет предоставлен к номерам, вышедшим с 1 числа следующего месяца за месяцем оплаты.') }}</p>
            </div>
            <button class="cm-button active" type="submit">{{ __('Оплатить') }}</button>
        </form>
    @endif
    @if($newspapers)
        @foreach($newspapers as $newspaper)
        <div>
            <p>
              Название: <b>{{ $newspaper->title }}; </b>
                Дата выпуска: <b>{{ $newspaper->release_at->format('d.m.Y') }}; </b>
                @if($newspaper->file ?? null && $newspaper->file->full_path ?? null)<a href="{{ $newspaper->file->full_path }}" target="_blank">Открыть</a>@endif
            </p>
        </div>
        @endforeach
    @endif
</div>
@endsection