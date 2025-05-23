@extends('frontend/layouts/default')
@section('content')

<div class="rubric-page-content posts-list-page">
    <div class="container">
        <div class="rubric-title page-title">
            <h2>{{ __('Новости') }}</h2>
        </div>
        <div class="rubric-item1">
            @foreach($posts as $post)
                @include('frontend.partials.list_item', [
                    'item' => $post,
                    'link' => route('post-single', $post->slug),
                    'item_title' => $post->title_short,
                    'item_subtitle' => null,
                ])
            @endforeach
        </div>
        <div class="pagination-container">
            {{ $posts->links() }}
        </div>
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
