@extends('frontend/v3/layouts/default')
@section('content')
    <div class="">
        @include('frontend.v3.partials.page_title', [
            'title' => __('Видеорепортажи'),
        ])
        <div class="grid grid-cols-12 gap-5">
            @foreach($video_articles as $video_article)
                @include('frontend.v3.partials.list_item', [
                    'item' => $video_article,
                    'link' =>  route('post-single', $video_article->slug),
                    'item_title' => $video_article->title,
                    'item_subtitle' => null,
                ])
            @endforeach
        </div>
        @if(isset($video_articles))
            @include('frontend.v3.partials.pagination_container', [
            'models' => $video_articles,
        ])
            @endif
    </div>

    <div class="py-5">
        @include('frontend.v3.partials.subscribe_lead_2')
    </div>

@endsection
