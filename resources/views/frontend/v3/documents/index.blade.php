@extends('frontend/v3/layouts/default')
@section('content')
<div class="">
    @include('frontend.v3.partials.page_title', [
        'title' => __('Документы'),
    ])

    <main class="max-w-7xl mx-auto py-5 px-1.5 sm:px-5" >
        <div class="doc-items flex-col">
            @foreach($documents as $document)
                <div class="doc-item p-4" style="background: #fff;">
                    <a href="{{route('documentSingle', $document->id)}}">
                        {{$document->title}}
                    </a>
                </div>
            @endforeach
        </div>
    </main>
    @include('frontend.v3.partials.pagination_container', [
        'models' => $documents,
    ])
</div>

<div class="py-5">
    @include('frontend.v3.partials.subscribe_lead_2')
</div>

@endsection
