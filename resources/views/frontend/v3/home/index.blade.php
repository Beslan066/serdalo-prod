@extends('frontend/v3/layouts/default')
@section('content')
    <div class="cm-home-page grid grid-cols-10 gap-5 h-fit">
        @if($material_sticky)
            @php
                $material_sticky_image_path = '';
                if($material_sticky->promote_with_file && $material_sticky->file) {
                    if($material_sticky->file->type === 'video' && $material_sticky->thumb) {
                        $material_sticky_image_path = $material_sticky->thumb->full_path;
                    } else {
                        $material_sticky_image_path = $material_sticky->file->full_preview_path;
                    }
                }
            @endphp
            <div class="sm:hidden col-span-10">
                <div class="flex h-full font-semibold">
                    <div class="relative w-full h-full">
                        @include('frontend.v3.partials.article_media', [
                            'model' => $material_sticky,
                            'link' => route('material-single', $material_sticky->slug),
                            'icon_align' => 'justify-center items-start',
                            'icon_size' => 'w-14 h-14 md:w-20 md:h-20',
                            'classes' => 'w-full cm-aspect-16/9 sm:cm-aspect-4/3 md:cm-aspect-16/9'
                        ])
                        <div class="absolute left-0 bottom-0 w-full h-full flex items-end pointer-events-none">
                            <div class="flex flex-col justify-end w-full min-h-1/2 p-5 color-1 cm-bd-gradient-1">
                                <h4><a href="{{ route('material-single', $material_sticky->slug) }}"
                                       class="mb-2.5 text-3xl sm:text-lg md:text-2xl lg:text-3xl pointer-events-auto">{{ $material_sticky->subtitle_short }}</a></h4>
                                <h3><span
                                        class="cm-article-subtitle pointer-events-auto">{{ $material_sticky->title_short }}</span></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif
    @include('frontend.v3.partials.news_sidebar', [
        'posts' => $posts_main,
    ])
    <!-- main block -->
        <div class="cm-home-page-right-col col-span-10 sm:col-span-7 lg:col-span-8 xl:col-span-8 h-fit">
            <!-- main news & materials -->
            <div class="grid grid-cols-2 gap-3.5 sticky-block">
                @if($material_sticky)
                    @php
                        $material_sticky_image_path = '';
                        if($material_sticky->promote_with_file && $material_sticky->file) {
                            if($material_sticky->file->type === 'video' && $material_sticky->thumb) {
                                $material_sticky_image_path = $material_sticky->thumb->full_path;
                            } else {
                                $material_sticky_image_path = $material_sticky->file->full_preview_path;
                            }
                        }
                    @endphp
                    <div class="hidden sm:block col-span-2 xl:col-span-1 row-span-2">
                        <div class="flex h-full font-semibold">
                            <div class="relative w-full h-full">
                                @include('frontend.v3.partials.article_media', [
                                    'model' => $material_sticky,
                                    'link' => route('material-single', $material_sticky->slug),
                                    'icon_align' => 'justify-center items-start',
                                    'icon_size' => 'w-14 h-14 md:w-20 md:h-20',
                                    'classes' => 'w-full cm-aspect-16/9 md:cm-aspect-16/9'
                                ])
                                <div class="absolute left-0 bottom-0 w-full h-full flex items-end pointer-events-none">
                                    <div
                                        class="flex flex-col justify-end w-full min-h-1/2 p-5 color-1 cm-bd-gradient-1">
                                        <h4><a href="{{ route('material-single', $material_sticky->slug) }}"
                                               class="mb-2.5 text-base sm:text-3xl md:text-3xl lg:text-5xl pointer-events-auto">{{ $material_sticky->subtitle_short }}</a></h4>
                                        <h3><span
                                                class="cm-article-subtitle pointer-events-auto">{{ $material_sticky->title_short }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(count($materials_main_left))
                    @foreach($materials_main_left as $material_main_left)
                        @include('frontend.v3.partials.material_main', [
                            'model' => $material_main_left,
                            'show_mobile' => ($loop->iteration > 1 && !($loop->iteration === 3 && $material_main_left->promote_with_file && $material_main_left->file)) ? false : true,
                            'show_file' => ($loop->iteration === 3 && $material_main_left->promote_with_file && $material_main_left->file) ? true : false
                        ])
                    @endforeach
                @endif
                @if(count($materials_main_right))
                    @foreach($materials_main_right as $material_main_right)
                        @include('frontend.v3.partials.material_main', [
                            'model' => $material_main_right,
                            'show_mobile' => ($loop->iteration > 1 && !($loop->iteration === 4 && $material_main_right->promote_with_file && $material_main_right->file)) ? false : true,
                            'show_file' => ($loop->iteration === 4 && $material_main_right->promote_with_file && $material_main_right->file) ? true : false
                        ])
                    @endforeach
                @endif
            </div>

        @include('frontend.v3.partials.subscribe_lead_1')

        @if(count($video_articles) || count($photo_articles))
            <!-- Видео & Фото -->
                <div class="cm-section-2 hidden xl:grid grid-cols-1 xl:grid-cols-2 gap-5 py-5">
                @if(count($video_articles))
                    <!-- Видео -->
                        <div class="cm-section-2-item cm-section-videos col-span-1 flex flex-col">
                            <div class="mb-5">
                                <h2><span class="text-3xl font-black color-7">Видео</span></h2>
                            </div>
                            <div class="h-full grid grid-cols-1 sm:grid-cols-2 gap-5">
                                @foreach($video_articles as $video_article)
                                    <div
                                        class="h-full cm-section-videos-item col-span-1 flex flex-col bg-1 overflow-hidden">
                                        @include('frontend.v3.partials.article_media', [
                                            'model' => $video_article,
                                            'link' => route('post-single', $video_article->slug),
                                            'icon_size' => 'w-20 h-20 sm:w-14 sm:h-14 lg:w-20 lg:h-20 xl:w-14 xl:h-14',
                                            'classes' => 'flex-1 cm-aspect-16/9 xl:cm-aspect-4/3'
                                        ])
                                        <div class="flex flex-col h-full justify-between p-5">
                                            <h3><a href="{{ route('post-single', $video_article->slug) }}"
                                                   class="cm-article-title">{{ $video_article->title_short }}</a></h3>
                                            @if(\Carbon\Carbon::parse($video_article->published_at)->isToday())
                                                <span class="cm-article-date">
                                                    {{ \Carbon\Carbon::parse($video_article->published_at)->format('H:i')}}
                                                </span>
                                            @else
                                                <span class="cm-article-date">
                                                     {{ \Carbon\Carbon::parse($video_article->published_at)->format('d.m.Y H:i')}}
                                                </span>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-5 more-media-link">
                                <a href="{{route('videoreportage')}}" class="border-b border-color-7 color-7">{{ __('Смотреть все видео') }}</a>
                            </div>
                        </div>
                @endif

                @if(count($photo_articles))
                    <!-- Фото -->
                        <div class="cm-section-2-item cm-section-photos col-span-1 flex flex-col">
                            <div class="mb-5">
                                <h2><span class="text-3xl font-black color-7">Фото</span></h2>
                            </div>
                            <div class="h-full grid grid-cols-1 sm:grid-cols-2 gap-5">

                                @foreach($photo_articles as $photo_article)

                                    <div
                                        class="h-full cm-section-photos-item col-span-1 flex flex-col bg-1 overflow-hidden">
                                        @include('frontend.v3.partials.article_media', [
                                            'model' => $photo_article,
                                            'link' => route('post-single', $photo_article->id),
                                            'icon_size' => 'w-20 h-20 sm:w-14 sm:h-14 lg:w-20 lg:h-20 xl:w-14 xl:h-14',
                                            'classes' => 'flex-1 cm-aspect-16/9 xl:cm-aspect-4/3'
                                        ])
                                        <div class="flex flex-col h-full justify-between p-5">
                                            <h3><a href="{{ route('reportage-single', $photo_article->id) }}"
                                                   class="cm-article-title">{{ $photo_article->title }}</a></h3>
                                            @if(\Carbon\Carbon::parse($photo_article->published_at)->isToday())
                                                <span class="cm-article-date">
                                                    {{ \Carbon\Carbon::parse($photo_article->published_at)->format('H:i')}}
                                                </span>
                                            @else
                                                <span class="cm-article-date">
                                                     {{ \Carbon\Carbon::parse($photo_article->published_at)->format('d.m.Y H:i')}}
                                                </span>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="mt-5  more-media-link">
                                <a href="{{route('reportages')}}" class="border-b border-color-7 color-7">{{ __('Смотреть все фото') }}</a>
                            </div>
                        </div>
                @endif
                </div>
            @endif
        </div>
    </div>
    @if(count($video_articles) || count($photo_articles))
        <!-- Видео & Фото -->
        <div class="cm-section-2 grid xl:hidden grid-cols-1 sm:grid-cols-3 gap-5 py-5">
        @if(count($video_articles))
            <!-- Видео -->
                <div class="cm-section-2-item cm-section-videos col-span-2 flex flex-col">
                    <div class="mb-5">
                                <h2><span class="text-3xl font-black color-7">Видео</span></h2>
                            </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($video_articles as $video_article)
                            <div class="cm-section-videos-item col-span-1 flex flex-col bg-1 overflow-hidden">
                                @include('frontend.v3.partials.article_media', [
                                    'model' => $video_article,
                                    'link' => route('post-single', $video_article->slug),
                                    'icon_size' => 'w-20 h-20 sm:w-14 sm:h-14 lg:w-20 lg:h-20 xl:w-14 xl:h-14',
                                    'classes' => 'flex-1 cm-aspect-16/9 xl:cm-aspect-4/3'
                                ])
                                <div class="flex flex-col h-full justify-between p-5">
                                    <h3><a href="{{ route('post-single', $video_article->slug) }}"
                                           class="cm-article-title">{{ $video_article->title_short }}</a></h3>
                                    <span class="cm-article-date">{{ $video_article->display_created_at }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                                <div class="mt-5">
                                    <a href="{{route('videoreportage')}}" class="border-b border-color-7 color-7">{{ __('Смотреть все видео') }}</a>
                                </div>
                </div>
        @endif
        @if(count($photo_articles))
            <!-- Видео -->
                <div class="cm-section-2-item cm-section-photos col-span-1 flex flex-col">
                <div class="mb-5">
                                <h2><span class="text-3xl font-black color-7">Фото</span></h2>
                            </div>
                    <div class="grid grid-cols-1 gap-5">
                        @foreach($photo_articles as $photo_article)

                            <div class="cm-section-photos-item col-span-1 flex flex-col bg-1 overflow-hidden">
                                @include('frontend.v3.partials.photo_article', [
                                    'model' => $photo_article,
                                    'link' => route('reportage-single', $photo_article->id),
                                    'icon_size' => 'w-20 h-20 sm:w-14 sm:h-14 lg:w-20 lg:h-20 xl:w-14 xl:h-14',
                                    'classes' => 'flex-1 cm-aspect-16/9 xl:cm-aspect-4/3'
                                ])
                                <div class="flex flex-col h-full justify-between p-5">
                                    <h3><a href="{{ route('reportage-single', $photo_article->id) }}"
                                           class="cm-article-title">{{ $photo_article->title }}</a></h3>
{{--                                    <span class="cm-article-date">{{ $photo_article->published_at }}</span>--}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-5 more-media-link">
                        <a href="{{route('reportages')}}" class="border-b border-color-7 color-7">{{ __('Смотреть все фото') }}</a>
                    </div>
                </div>
            @endif
        </div>
    @endif

    @if(count($journalism))
        <!-- Не пропустите -->
        <div class="cm-section-3 py-5">
        <div class="mb-5">
                                <h2><span class="text-3xl font-black color-7">Публицистика</span></h2>
                            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach($journalism as $item)
                    <div
                        class="cm-section-3-item anons-news col-span-1 row-span-2 flex flex-col h-full bg-1 overflow-hidden">
                        @include('frontend.v3.partials.article_media', [
                            'model' => $item,
                            'link' => route('material-single', $item->slug),
                            'classes' => 'flex-1 cm-aspect-16/9 xl:cm-aspect-4/3'
                        ])
                        <div class=" flex flex-col h-full justify-between p-5">
                            @if(App::getlocale() === 'ru')
                                <div class="flex flex-col mb-2.5">
                                    <h4><a href="{{ route('material-single', $item->slug) }}"
                                           class="cm-article-title">{{ $item->subtitle_short }}</a></h4>
                                    <h3><span class="cm-article-subtitle">{{ $item->title_short }}</span></h3>
                                </div>
                            @else
                                <div class="flex flex-col mb-2.5">
                                    <h4><a href="{{ route('journalism-single', $item->slug) }}"
                                           class="cm-article-title">{{ $item->subtitle }}</a></h4>
                                    <h3><span class="cm-article-subtitle">{{ $item->title }}</span></h3>
                                </div>
                            @endif
                            <span class="cm-article-date">{{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y H:i')}}</span>

                        </div>
                    </div>
                @endforeach

            </div>
            <div class="mt-5 more-media-link flex justify-center">
                <a href="{{route('journalism-index')}}" class="border-b border-color-7 color-7">{{ __('Смотреть все') }}</a>
            </div>
        </div>
    @endif

    @if(count($materials_popular))
        <!-- Не пропустите -->
        <div class="cm-section-3 py-5">
        <div class="mb-5">
                                <h2><span class="text-3xl font-black color-7">Не пропустите</span></h2>
                            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach($materials_popular as $material_popular)
                    <div
                        class="cm-section-3-item anons-news col-span-1 row-span-2 flex flex-col h-full bg-1 overflow-hidden">
                        @include('frontend.v3.partials.article_media', [
                            'model' => $material_popular,
                            'link' => route('material-single', $material_popular->slug),
                            'classes' => 'flex-1 cm-aspect-16/9 xl:cm-aspect-4/3'
                        ])
                        <div class=" flex flex-col h-full justify-between p-5">
                            <div class="flex flex-col mb-2.5">
                                <h4><a href="{{ route('material-single', $material_popular->slug) }}"
                                       class="cm-article-title">{{ $material_popular->subtitle_short }}</a></h4>
                                <h3><span class="cm-article-subtitle">{{ $material_popular->title_short }}</span></h3>
                            </div>
                            <span class="cm-article-date">{{ $material_popular->display_created_at }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @include('frontend.v3.partials.subscribe_lead_2')

    <!-- Музей -->
    <!--<div class="cm-section-4 py-5">-->
    <!--    <div class="grid grid-cols-12 gap-5">-->
    <!--        <div class="cm-section-4-item col-span-12 md:col-span-6 flex flex-col sm:flex-row md:flex-col lg:flex-row h-full bg-1">-->
    <!--            <div class="basis-2/5 w-full h-full overflow-hidden">-->
    <!--                <img class="flex-1 w-full cm-aspect-16/9 sm:cm-aspect-3/4 md:cm-aspect-16/9 lg:cm-aspect-3/4 object-cover" src="{{ asset('frontend/v3/assets/media/demo/1.jpg') }}">-->
    <!--            </div>-->
    <!--            <div class="basis-3/5 flex flex-col justify-between p-5">-->
    <!--                <div class="flex flex-col mb-5">-->
    <!--                    <a href="#" class="cm-article-title">news itemitemitem title 9</a>-->
    <!--                    <span class="cm-article-subtitle">news itemitemitem short desc news itemitemitem short desc short desc ...</span>-->
    <!--                </div>-->
    <!--                <div class="">-->
    <!--                    <span class="cm-article-date">17.06.2023</span>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="cm-section-4-item col-span-12 md:col-span-6 flex flex-col sm:flex-row md:flex-col lg:flex-row h-full bg-1">-->
    <!--            <div class="basis-2/5 w-full h-full overflow-hidden">-->
    <!--                <img class="flex-1 w-full cm-aspect-16/9 sm:cm-aspect-3/4 md:cm-aspect-16/9 lg:cm-aspect-3/4 object-cover" src="{{ asset('frontend/v3/assets/media/demo/2.jpg') }}">-->
    <!--            </div>-->
    <!--            <div class="basis-3/5 flex flex-col justify-between p-5">-->
    <!--                <div class="flex flex-col mb-5">-->
    <!--                    <a href="#" class="cm-article-title">news itemitemitem title itemitemitem 9</a>-->
    <!--                    <span class="cm-article-subtitle">news itemitemitem short desc news itemitemitem short desc short desc ...</span>-->
    <!--                </div>-->
    <!--                <div class="">-->
    <!--                    <span class="cm-article-date">17.06.2023</span>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- Видеостудия "Сердало" -->
    {{--@if(count($video_studio_section_articles))--}}
    {{--<div class="cm-section-5 py-5">--}}
    {{--    @include('frontend.v3.partials.page_title', [--}}
    {{--        'title' => __('Видеостудия "Сердало"'),--}}
    {{--    ])--}}
    {{--    <div class="grid grid-cols-12 gap-5">--}}
    {{--        @foreach($video_studio_section_articles as $video_studio_section_article)--}}

    {{--              <div class="cm-section-5-item video-article-none col-span-12 md:col-span-6 lg:col-span-4 flex flex-col bg-1">--}}
    {{--                <a href="#" class="cm-article-title cursor-pointer">--}}
    {{--                    @include('frontend.v3.partials.article_media', [--}}
    {{--                        'model' => $video_studio_section_article,--}}
    {{--                        'link' => '#',--}}
    {{--                        'classes' => 'w-full aspect-video object-cover cursor-pointer'--}}
    {{--                    ])--}}
    {{--                </a>--}}
    {{--                <div class="flex flex-col h-full justify-between p-5">--}}
    {{--                    <div class="flex flex-col mb-5">--}}
    {{--                        <a href="#" class="cm-article-title">{{ $video_studio_section_article->title }}</a>--}}
    {{--                    </div>--}}
    {{--                    <div class="">--}}
    {{--                        <span class="cm-article-date">{{ $video_studio_section_article->display_created_at }}</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--              </div>--}}

    {{--        @endforeach--}}
    {{--    </div>--}}
    {{--</div>--}}
    {{--@endif--}}
    <!-- Литературный салон -->
    {{--@if(count($litsalon_section_articles))--}}
    {{--<div class="cm-section-6 py-5">--}}
    {{--    @include('frontend.v3.partials.page_title', [--}}
    {{--        'title' => __('Литературный салон'),--}}
    {{--    ])--}}
    {{--    <div class="grid grid-cols-12 gap-5">--}}
    {{--        @foreach($litsalon_section_articles as $litsalon_section_article)--}}
    {{--            <div class="cm-section-6-item lit-home-item col-span-12 md:col-span-6 lg:col-span-4 flex flex-row h-full bg-1 overflow-hidden">--}}
    {{--                <div class="basis-1/2 w-full h-full">--}}
    {{--                    @include('frontend.v3.partials.article_media', [--}}
    {{--                        'model' => $litsalon_section_article,--}}
    {{--                        'link' => '#',--}}
    {{--                        'classes' => ' w-full cm-aspect-2/4 object-cover'--}}
    {{--                    ])--}}
    {{--                </div>--}}
    {{--                <div class="basis-1/2 flex flex-col justify-between bg-7 p-5">--}}
    {{--                    <div class="flex flex-col">--}}
    {{--                        <a href="{{route('litSingle', $litsalon_section_article->id)}}" class="mb-2.5 text-xl md:text-base xl:text-lg color-1 font-semibold">{{ $litsalon_section_article->title }}</a>--}}
    {{--                    </div>--}}
    {{--                    <div class="">--}}
    {{--                        <span class="block mb-2.5 color-1">{{ $litsalon_section_article->subtitle }}</span>--}}
    {{--                        <span class="cm-article-date color-5">{{ $litsalon_section_article->dates }}</span>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        @endforeach--}}
    {{--    </div>--}}
    {{--</div>--}}
    {{--@endif--}}
@endsection
