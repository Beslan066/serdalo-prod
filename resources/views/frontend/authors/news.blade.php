@extends('frontend/layouts/default')
<link rel="stylesheet" href="{{asset('frontend/css/author-news.css')}}">
@section('content')

    <div class="container author-news-container">


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

        <div class="page-tab-links">
            <button class="author-page-tab">
                <a href="{{route('authors-index', $author->id)}}">Статьи</a>
            </button>
            <button class="author-page-tab active">Новости</button>
        </div>


        <div class="rubric-item1 author-news">
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

@endsection
