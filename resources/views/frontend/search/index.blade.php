@extends('frontend/layouts/default')
@section('content')

<div class="rubric-page-content posts-list-page">
    <div class="container">
        <div class="rubric-title page-title">
            <h2>{{ __('Поиск') }}</h2>
        </div>
        <div class="rubric-title page-title">
            <h3>{{ $subtitle }}</h3>
        </div>
        <div class="rubric-item1">
            @foreach($items as $item)
                @php
                if($item->type === 'post') {
                    $item_link = route('post-single', $item->slug);
                    $item_title = $item->title_short;
                    $item_subtitle = null;
                } else {
                    $item_link = route('material-single', $item->slug);
                    $item_title = $item->subtitle;
                    $item_subtitle = $item->title;
                }
                @endphp
                @include('frontend.partials.list_item', [
                    'item' => $item,
                    'link' => $item_link,
                    'item_title' => $item_title,
                    'item_subtitle' => $item_subtitle,
                ])
            @endforeach
        </div>
        @if($items)
            <div class="pagination-container">
                {{ $items->links() }}
            </div>
        @endif
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
</div>
@endsection
