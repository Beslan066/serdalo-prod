@extends('frontend/layouts/default')
@section('content')
    <div class="container post-page-container">
        <div class="content">
            <div class="news-wrapper">
                <div class="news">
                    <h3>{{ __('Новости') }}</h3>
                    <hr style="margin-top: 23px">
                    @foreach($posts_main as $post_main)
                        <div class="news-block">
                            <span>{{ $post_main->display_created_at }}</span>
                            @if($post_main->promote_with_file && $post_main->file)
                                <div class="news-img">
                                    @if($post_main->file->type === 'video')
                                        <div class="bg-video-icon-container" data-video-path="{{ $post_main->file->full_path }}">
                                            <div class="bg-video-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                    <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/>
                                                </svg>
                                            </div>
                                            @if($post_main->thumb)
                                                <img src="{{ $post_main->thumb->full_path }}" alt="">
                                            @endif
                                        </div>
                                    @else
                                        <img src="{{ $post_main->file->full_preview_path }}" alt="">
                                    @endif
                                    <p class="news-img-title"><a href="{{ route('post-single', $post_main->slug) }}">{{ $post_main->title_short }}</a></p>
                                </div>
                            @else
                                <p><a href="{{ route('post-single', $post_main->slug) }}">{{ $post_main->title_short }}</a></p>
                            @endif
                        </div>
                        <hr>
                    @endforeach
                </div>
                <div class="materials">
                <div class="news-body">
                    @if($post->tags ?? [])
                        @foreach($post->tags ?? [] as $tag)
                            <span class="news-page-rubric">{{ $tag->title }}</span>
                        @endforeach
                    @endif
                    <h2 class="news-first-title">{{ $post->title }}</h2>
                    <div class="news-info">
                        @if($post->author)
                        <span class="news-author"><a href="{{ route('authors-index', $post->author->id) }}">{{ $post->author->title }}</a></span>
                        @endif
                        <span class="news-date">{{ $post->created_at->translatedFormat('j F Y, H:i') }}</span>
                    </div>
                    @if($post->file)
                        @if($post->file->type === 'video')
                            <div class="news-video">
                                <video controls="controls" preload="none">
                                    <source src="{{ $post->file->full_path }}" />
                                </video>
                            </div>
                        @else
                            <div class="news-img">
                                <img src="{{ $post->file->full_preview_path }}" alt="">
                            </div>
                        @endif
                    @endif
                    {{--
                    <div class="news-img-desc">
                        <div class="img-owner">Фото: REUTERS/Ronda Churchill</div>
                        <div class="img-desc">Сенатор Кэтрин Кортес-Масто после победы на выборах 13 ноября Сенатор Кэтрин Кортес-Масто после победы на выборах 13 ноября</div>
                    </div>
                    --}}
                    <div class="news-text">
                        <p class="lead">{{ $post->lead }}</p>
                        <div class="desc-section">
                            {!! $post->description !!}
                        </div>
                    </div>
                    @if($post->file)
                    <!--<div class="news-img2">
                        <img src="{{ $post->file->full_preview_path }}" alt="">
                    </div>-->
                    @endif
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
