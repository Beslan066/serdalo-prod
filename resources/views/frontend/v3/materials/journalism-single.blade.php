@extends('frontend/v3/layouts/default')
@section('content')
    <div class="cm-single-page grid grid-cols-10 gap-5">
        <div class="cm-news_sidebar-wrapper col-span-10 sm:col-span-3 lg:col-span-2 xl:col-span-2 xl:pb-5">
            <div class="cm-news_sidebar flex flex-col gap-3 bg-1 xl:py-2.5">
                <h3 class="text-2xl font-extrabold px-3">{{ __('Новости') }}</h3>
                @foreach($posts_main as $post_main)
                    <div class="cm-news_sidebar-item font-medium px-2.5 pb-2.5 border-b border-color-3 last:border-b-0">
                        @if(\Carbon\Carbon::parse($post_main->created_at)->isToday())
                            <span class="block mb-1.5 cm-article-date font-bold color-7">
                        {{ \Carbon\Carbon::parse($post_main->created_at)->format('H:i')}}
                    </span>
                        @else
                            <span class="block mb-1.5 cm-article-date font-bold color-7">
                        {{ \Carbon\Carbon::parse($post_main->created_at)->format('d.m.Y H:i')}}
                    </span>
                        @endif
                        @if($post_main->promote_with_file && $post_main->file)
                            <div class="relative">
                                @include('frontend.v3.partials.article_media', [
                                                            'model' => $post_main,
                                                            'link' => route('post-single', $post_main->slug),
                                                            'icon_align' => 'justify-center items-start',
                                                            'classes' => 'cm-aspect-16/9 sm:cm-aspect-3/4 md:cm-aspect-4/3 xl:cm-aspect-16/9'
                                                        ])
                                <div class="absolute left-0 bottom-0 w-full h-full flex items-end pointer-events-none">
                                    <div class="flex flex-col justify-end w-full min-h-1/2 p-2.5 color-1 cm-bd-gradient-1">
                                        <a href="{{ route('post-single', $post_main->slug) }}" class="cm-article-subtitle pointer-events-auto">{{ $post_main->title }}</a>
                                    </div>
                                </div>

                            </div>
                        @else
                            <a href="{{ route('post-single', $post_main->slug) }}" class="block cm-article-subtitle color-2">{{ $post_main->title }}</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- main block -->
        <div class="col-span-10 sm:col-span-7 lg:col-span-8 xl:col-span-8">
            <div class="cm-single-content container p-5 md:pr-10 lg:pr-20 bg-1">
                @if($material->category)
                    <div class="mb-2.5">
                        <a href="{{route('journalism-index')}}" class="inline-block py-1 px-2.5 text-sm bg-3 rounded-full">{{ $material->category->title }}</a>
                    </div>
                @endif
                @include('frontend.v3.partials.page_title', [
                    'title' => $material->subtitle,
                ])
                <p class="mb-1 text-base font-semibold">{{ $material->title }}</p>
                <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-5 mb-2.5">



                    @if($material->author)
                        <div class="">
                            <a class="inline-block flex-none font-semibold color-7 border-b border-color-7" href="{{ route('authors-index', $material->author->id) }}">{{ $material->author->title }}</a>
                        </div>
                    @endif
                    <div class="">

                        <span class="color-4">{{ \Carbon\Carbon::parse($material->published_at)->format('d.m.Y H:i')}}</span>
                    </div>
                </div>
                @if($material->file)
                    <div class="cm-content-media mb-5">
                        @if($material->file->type === 'video')
                            <div>
                                <video class="w-full" controls="controls" preload="none" poster="{{$material->thumb->full_preview_path}}">
                                    <source class="" src="{{ $material->file->full_path }}" />
                                </video>
                            </div>
                        @else
                            <div class="img-material-height">
                                <img class="cm-aspect-4/3 lg:aspect-auto object-cover" src="{{ $material->file->full_preview_path }}" alt="">
                            </div>

                            @if(isset($material->photo_title))
                                <div class="flex flex-row mt-4 justify-between">
                                    <div class="photo-author ">{{$material->photo_title}}</div>
                                    <div class="photo-description font-light">{{$material->photo_description}}</div>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
                <div class="single-text-center">
                    <div >
                        <p class="mb-10 text-base font-semibold">{{ $material->lead }}</p>
                    </div>
                    <div class="cm-single-description text-base">
                        {!! $material->description  !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-5">
        @include('frontend.v3.partials.subscribe_lead_2')
    </div>

@endsection
