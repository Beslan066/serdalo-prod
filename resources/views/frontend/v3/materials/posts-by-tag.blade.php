@extends('frontend/v3/layouts/default')
@section('content')
    <div class="">
        <div class="mb-5">

            <h1 class="text-3xl font-black color-7">{{$categoryTitle}}</h1>
        </div>


        <div class="grid grid-cols-12 gap-5">
            @foreach($posts as $post)
                @include('frontend.v3.partials.list_item', [
                    'item' => $post,
                    'link' => route('post-single', $post->slug),
                    'item_title' =>$post->subtitle,
                    'item_subtitle' => $post->title_short,
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
