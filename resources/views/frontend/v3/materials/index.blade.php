@extends('frontend/v3/layouts/default')
@section('content')
<div class="">
    <div class="mb-5">
        <h1 class="text-3xl font-black color-7">{{$categoryTitle}}</span>
    </div>

    <div class="grid grid-cols-12 gap-5">
        @foreach($materials as $material)
            @include('frontend.v3.partials.list_item', [
                'item' => $material,
                'link' => route('material-single', $material->slug),
                'item_title' => $material->subtitle_short,
                'item_subtitle' => $material->title_short,
            ])
        @endforeach
    </div>
    @include('frontend.v3.partials.pagination_container', [
        'models' => $materials,
    ])
</div>

<div class="py-5">
    @include('frontend.v3.partials.subscribe_lead_2')
</div>

@endsection
