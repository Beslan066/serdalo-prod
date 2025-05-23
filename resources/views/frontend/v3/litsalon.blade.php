@extends('frontend/v3/layouts/default')
@section('content')

    <div class="cm-section-6 py-5">
        <div class="mb-5">
            <span class="text-3xl font-black color-7">Литературный салон</span>
        </div>


        @if(isset($oldLitArticle))
            <div class="mb-5">
                <h3 class="generation-title">Старшее поколение</h3>
            </div>
            <div class="grid grid-cols-12 gap-5 mb-3">
                @foreach($oldLitArticle as $article)
                    <div
                        class="cm-section-6-item col-span-12 md:col-span-6 lg:col-span-4 flex flex-row h-full bg-1 overflow-hidden">
                        <div class="basis-1/2 w-full h-full">
                            @include('frontend.v3.partials.article_media', [
                                'model' => $article,
                                'link' => '#',
                                'classes' => 'w-full cm-aspect-2/4 object-cover'
                            ])
                        </div>
                        <div class="basis-1/2 flex flex-col justify-between bg-7 p-5">
                            <div class="flex flex-col">
                                <a href="{{route('litSingle', $article->id)}}" class="mb-2.5 text-xl md:text-base xl:text-lg color-1 font-semibold">{{$article->title}}</a>
                            </div>
                            <div class="">
                                <span class="block mb-2.5 color-1">{{ $article->subtitle }}</span>
                                <span class="cm-article-date color-5">{{ $article->dates }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif


        @if(isset($middleLitArticle))
            <div class="mb-5">
                <h3 class="generation-title">Среднее поколение</h3>
            </div>
            <div class="grid grid-cols-12 gap-5 mb-3">
                @foreach($middleLitArticle as $article)
                    <div
                        class="cm-section-6-item col-span-12 md:col-span-6 lg:col-span-4 flex flex-row h-full bg-1 overflow-hidden">
                        <div class="basis-1/2 w-full h-full">
                            @include('frontend.v3.partials.article_media', [
                                'model' => $article,
                                'link' => '#',
                                'classes' => 'w-full cm-aspect-3/4 object-cover'
                            ])
                        </div>
                        <div class="basis-1/2 flex flex-col justify-between bg-7 p-5">
                            <div class="flex flex-col">
                                <a href="{{route('litSingle', $article->id)}}" class="mb-2.5 text-xl md:text-base xl:text-lg color-1 font-semibold">{{$article->title}}</a>
                            </div>
                            <div class="">
                                <span class="block mb-2.5 color-1">{{ $article->subtitle }}</span>
                                <span class="cm-article-date color-5">{{ $article->dates }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(isset($youngerLitArticle))
            <div class="mb-5">
                <h3 class="generation-title">Младшее поколение</h3>
            </div>
            <div class="grid grid-cols-12 gap-5 mb-3">
                @foreach($youngerLitArticle as $article)
                    <div
                        class="cm-section-6-item col-span-12 md:col-span-6 lg:col-span-4 flex flex-row h-full bg-1 overflow-hidden">
                        <div class="basis-1/2 w-full h-full">
                            @include('frontend.v3.partials.article_media', [
                                'model' => $article,
                                'link' => '#',
                                'classes' => 'w-full cm-aspect-3/4 object-cover'
                            ])
                        </div>
                        <div class="basis-1/2 flex flex-col justify-between bg-7 p-5">
                            <div class="flex flex-col">
                                <a href="{{route('litSingle', $article->id)}}" class="mb-2.5 text-xl md:text-base xl:text-lg color-1 font-semibold">{{$article->title}}</a>
                            </div>
                            <div class="">
                                <span class="block mb-2.5 color-1">{{ $article->subtitle }}</span>
                                <span class="cm-article-date color-5">{{ $article->dates }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
