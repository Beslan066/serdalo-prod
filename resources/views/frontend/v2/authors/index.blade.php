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
            <div class="page-tab-links">
                <button class="author-page-tab active">
                    Статьи
                </button>
                <button class="author-page-tab">
                    <a href="{{route('author-news', $author->id)}}">Новости</a>
                </button>
            </div>
            <div class="rubric-item1 author-materials">
                @foreach($materials as $material)
                    @include('frontend.partials.list_item', [
                        'item' => $material,
                        'link' => route('material-single', $material->slug),
                        'item_title' => $material->subtitle,
                        'item_subtitle' => $material->title,
                    ])
                @endforeach
            </div>
            <div class="pagination-container">
                {{ $materials->links() }}
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
</div>
@endsection
