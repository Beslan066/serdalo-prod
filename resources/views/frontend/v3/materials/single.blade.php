@extends('frontend/v3/layouts/default')
@section('content')
<div class="cm-single-page grid grid-cols-10 gap-5">
    @include('frontend.v3.partials.news_sidebar', [
        'posts' => $posts_main,
    ])
    <!-- main block -->
    <div class="col-span-10 sm:col-span-7 lg:col-span-8 xl:col-span-8">
        <div class="cm-single-content container p-5 md:pr-10 lg:pr-20 bg-1">
            @if($material->category)
                <div class="mb-2.5">
                    <a href="{{route('materials-index', $material->category->id)}}" class="inline-block py-1 px-2.5 text-sm bg-3 rounded-full">{{ $material->category->title }}</a>
                </div>
            @endif
            @include('frontend.v3.partials.page_title', [
                'title' => $material->subtitle,
            ])
            <h1 class="mb-1 text-base font-semibold">{{ $material->title }}</h1>
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

                @if((App::getlocale() === 'ru'))
                    @if(!empty($material->comment))
                        <div class="post-expert-section flex ">
                            @if($material->expert && $material->expert->file)
                                <div class="expert-img">
                                    <img src="{{ $material->expert->file->full_preview_path }}" alt="">
                                </div>
                            @endif

                            <div class="comment-body">

                                @if($material->expert)
                                    <div class="expert-info">
                                        <p>{{$material->expert->title}}</p>
                                        <p>{!! $material->expert->description !!}</p>
                                    </div>
                                @endif

                                <div class="comment">
                                    <p>{{$material->comment}}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

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
