@extends('frontend/v3/layouts/default')
@section('content')
<div class="">
    @include('frontend.v3.partials.page_title', [
        'title' => __('Поиск'),
    ])
    <h3 class="text-2xl font-semibold mb-5">{{ $subtitle }}</h3>
    <div class="grid grid-cols-12 gap-5">
        @foreach($items as $item)
            @php
            if($item->type === 'post') {
                $item_link = route('post-single', $item->slug);
                $item_title = $item->title_short;
                $item_subtitle = null;
            } else {
                $item_link = route('material-single', $item->slug);
                $item_title = $item->subtitle_short;
                $item_subtitle = $item->title_short;
            }
            @endphp
            @include('frontend.v3.partials.list_item', [
                'item' => $item,
                'link' => $item_link,
                'item_title' => $item_title,
                'item_subtitle' => $item_subtitle,
            ])
        @endforeach
    </div>
    @include('frontend.v3.partials.pagination_container', [
        'models' => $items,
    ])
</div>
@endsection