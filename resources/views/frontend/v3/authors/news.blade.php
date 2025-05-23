@extends('frontend/v3/layouts/default')
@section('content')
<div class="">
    {{--
    @include('frontend.v3.partials.page_title', [
        'title' => $title,
    ])
    --}}
    @if($author)
        <div class="flex gap-5 items-center mb-5">
            @if($author->file)
                <div class="w-36 h-36">
                    <img class="w-full h-full rounded-full object-cover" src="{{ $author->file->full_preview_path }}" alt="">
                </div>
            @endif
            <div class="">
                <h3 class="text-4xl font-bold color-7">{{ $author->title }}</h3>
                @if($author->description)
                    <span class="font-semibold">{!! $author->description !!}</span>
                @endif
            </div>
        </div>
    @endif
    <div class="mb-5">
        <button class="cm-button">
            <a href="{{route('authors-index', $author->id)}}">{{ __('Статьи') }}</a>
        </button>
        <button class="cm-button active">
            <a>{{ __('Новости') }}</a>
        </button>
    </div>
    <div class="grid grid-cols-12 gap-5">
        @foreach($posts as $post)
            @include('frontend.v3.partials.list_item', [
                'item' => $post,
                'link' => route('post-single', $post->slug),
                'item_title' => $post->title_short,
                'item_subtitle' => null,
            ])
        @endforeach
    </div>
    @include('frontend.v3.partials.pagination_container', [
        'models' => $posts,
    ])
</div>

<div class="py-5">
    @include('frontend.v3.partials.subscribe_lead_2')
</div>

@endsection
