@extends('frontend/v3/layouts/default')
@section('content')

    <main class="max-w-7xl mx-auto py-5 px-1.5 sm:px-5">

        @if(count($journalism))
            <div class="cm-section-5 py-5">
                @include('frontend.v3.partials.page_title', [
                    'title' => __('Публицистика'),
                ])
                <div class="grid grid-cols-12 gap-5">
                    @foreach($journalism as $material)

                        <div class="cm-section-5-item col-span-12 md:col-span-6 lg:col-span-4 flex flex-col bg-1">
                            <a href="#" class="cm-article-title cursor-pointer">
                                @include('frontend.v3.partials.article_media', [
                                    'model' => $material,
                                    'link' => route('material-single', $material->slug),
                                    'classes' => 'w-full aspect-video object-cover cursor-pointer'
                                ])
                            </a>
                            <div class="flex flex-col h-full justify-between p-5">
                                <div class="flex flex-col mb-5">
                                    @if(App::getLocale() == 'ru')
                                        <a href="{{route('material-single', $material->slug)}}" class="cm-article-title">
                                            {{ $material->subtitle }}
                                        </a>
                                    @else
                                        <a href="{{route('journalism-single', $material->slug)}}" class="cm-article-title">
                                            {{ $material->subtitle }}
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    <p>{{$material->title}}</p>
                                </div>
                                <div class="">
                                    @if(\Carbon\Carbon::parse($material->published_at)->isToday())
                                        <span class="cm-article-date">
                                         {{ \Carbon\Carbon::parse($material->published_at)->format('H:i')}}
                                    </span>
                                    @else
                                        <span class="cm-article-date">
                                        {{ \Carbon\Carbon::parse($material->published_at)->format('d.m.Y H:i')}}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
                @include('frontend.v3.partials.pagination_container', [
        'models' => $journalism,
    ])
            </div>
        @endif

    </main>

@endsection
