@extends('frontend/v3/layouts/default')
@section('content')
    <div class="">
        <main class="max-w-7xl mx-auto py-5 px-1.5 sm:px-5" style="background: #fff;">
            <div class="doc-items flex-col">

                    <div class="doc-item">
                        <h2 class="mb-10 text-3xl font-bold dark:text-white">{{$document->title}}</h2>

                    </div>

                <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-5 mb-2.5">
                    <div class="">
                        @if(isset($document->file->path))
                            <a class="inline-block flex-none font-semibold color-7 border-b border-color-7" href="https://serdalo.ru/storage/{{$document->file->path}}">Скачать</a>                        @endif
                    </div>

                    <div class="">
                        <span class="color-4 font-semibold">Опубликован: {{ \Carbon\Carbon::parse($document->signed_at)->format('d.m.Y')}}</span>
                        <span class="color-4 font-semibold">Опубликован: {{ \Carbon\Carbon::parse($document->published_at)->format('d.m.Y')}}</span>
                    </div>
                </div>

                    <div class="document-text">
                        <p>
                            {!! $document->description !!}
                        </p>
                    </div>
            </div>
        </main>

    </div>

    <div class="py-5">
        @include('frontend.v3.partials.subscribe_lead_2')
    </div>

@endsection
