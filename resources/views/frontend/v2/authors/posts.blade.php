@extends('frontend/layouts/default')
@section('content')
<div class="rubric-page-content author-page-container">
    <div class="container">
        @if($author)
            <div class="author-info">
                @if($author->file)
                    <div class="author-avatar">
                        <img src="{{ $author->file->full_preview_path }}" alt="">
                    </div>
                @endif
                <div class="author-data">
                    <h3 class="autor-name">{{ $author->title }}</h3>
                    @if($author->description)<span class="autor-name">{{ $author->description }}</span>@endif
                </div>
            </div>
        @endif
        @if($materials)
            <h2 class="autor-name" style="margin-bottom: 5px; margin-top: 5px;">{{ __('Статьи') }}</h2>
        @endif
        <div class="rubric-item1">
            @foreach($materials as $material)
                @php
                if($loop->iteration > 4) {
                    continue;
                }
                @endphp
                <div class="material">
                    <a href="{{ route('material-single', $material->slug) }}" class="material-link">
                        <h2 class="material-image-h2">{{ $material->subtitle }}</h2>
                        @if($material->title)<p>{{ TextHelper::short($material->title, 120) }}</p>@endif
                    </a>
                    <span class="date-info">{{ $material->created_at->translatedFormat('j F Y, H:i') }}</span>
                </div>
            @endforeach
        </div>
        @if(count($materials) > 4)
            <h3 class="autor-name" style="margin-bottom: 10px; margin-top: 5px;"><a href="{{ route('authors-index', $author->id) }}">{{ __('Смотреть все') }}</a></h3>
        @endif
        @if($posts)
            <h2 class="autor-name" style="margin-bottom: 5px; margin-top: 10px;">{{ __('Новости') }}</h2>
        @endif
        <div class="rubric-item1">
            @foreach($posts as $post)
                @include('frontend.partials.list_item', [
                    'item' => $post,
                    'link' => route('post-single', $post->slug),
                    'item_title' => $post->title,
                    'item_subtitle' => null,
                ])
            @endforeach
        </div>
        <div class="pagination-container">
            {{ $posts->links() }}
        </div>
    </div>
    <div class="rubric-subscribe">
        <div class="subscribe-banner">
            <div class="container">
                <div class="text">
                    <h2>{{ __('Подпишитесь на электронную газету') }}</h2>
                    <p>{{ __('(PDF) версия еженедельной общенациональной газеты «Сердало» и получайте свежие номера не выходя из дома!') }}</p>
                    <button><a href="{{route('payment-page')}}">{{ __('Подписаться') }}</a></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
