@extends('frontend/v2/layouts/default')
@section('content')
<div class="container index-page-container">
    <div class="content">
        <div class="news-wrapper">

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

                <a href="{{ route('material-single', $material_sticky->slug) }}" class="main-material-novisible">
                    <div class="main-material " @if($material_sticky_image_path) style="background: url('{{ $material_sticky_image_path }}') no-repeat center" @endif>
                        <div class="main-desc">

                            <h2>{{ $material_sticky->subtitle }}</h2>
                            <h3>{{ $material_sticky->title_short }}</h3>

                        </div>
                    </div>
                </a>
            @endif

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
                <div class="material-wrapper">
                    <div class="material-section">
                        <div class="material-section-left">
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

                                <a href="{{ route('material-single', $material_sticky->slug) }}">
                                    <div class="main-material" @if($material_sticky_image_path) style="background: url('{{ $material_sticky_image_path }}') no-repeat center" @endif>
                                        <div class="main-desc">

                                                <h2>{{ $material_sticky->subtitle }}</h2>
                                                <h3>{{ $material_sticky->title_short }}</h3>

                                        </div>
                                    </div>
                                </a>
                            @endif
                            @if($materials_main_left)
                                @foreach($materials_main_left as $material_main_left)
                                    @include('frontend.partials.material_main', [
                                        'model' => $material_main_left,
                                        'show_file' => ($loop->first && $material_main_left->promote_with_file && $material_main_left->file) ? true : false
                                    ])
                                @endforeach
                            @endif
                        </div>
                        <div class="material-section-right">
                            @if($materials_main_right)
                                @php
                                    $show_right_photo = null;
                                @endphp
                                @foreach($materials_main_right as $material_main_right)
                                    @php
                                        if($show_right_photo === null && $material_main_right->promote_with_file && $material_main_right->file) {
                                            $show_right_photo = true;
                                        } elseif($show_right_photo === true) {
                                            $show_right_photo = false;
                                        }
                                    @endphp
                                    @include('frontend.partials.material_main', [
                                        'model' => $material_main_right,
                                        'show_file' => ($loop->last && $material_main_right->promote_with_file && $material_main_right->file) ? true : false
                                    ])
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="subscribe">
                    <h3>{{ __('Подпишитесь на линию надежной информации.') }}</h3>
                    <p>{{ __('Оставьте ваш e-mail, чтобы получать подборки наших новостей.') }}</p>
                    <form>
                        <input type="text" placeholder="{{ __('Электронная почта') }}">
                        <button type="submit">{{ __('Подписаться') }}</button>
                    </form>
                    <div class="subscribe-checkbox">
                        <input type="checkbox" id="checkbox">
                        <label for="checkbox">{{ __('Я согласен на обработку персональных данных') }}</label>
                    </div>
                </div>
                <div class="media">
                    <div class="photo">
                        <div class="photoblock-title">
                            <h3>{{ __('Видео') }}</h3>
                        </div>
                        <div class="photo-blocks">
                            @if($video_articles)
                                @foreach($video_articles as $video_article)
                                    <div class="photo-block">
                                        <div class="media-capture">
                                            <div class="bg-video-icon-container" @if($video_article->file) data-video-path="{{ $video_article->file->full_path }}" @endif>
                                                <div class="bg-video-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                        <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                        <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/>
                                                    </svg>
                                                </div>
                                                @if($video_article->thumb)
                                                    <img src="{{ $video_article->thumb->full_path }}" alt="">
                                                @endif
                                            </div>
                                        </div>
                                        <h3 class="media-title"><a href="{{ route('post-single', $video_article->slug) }}">{{ $video_article->title_short }}</a></h3>
                                        <span class="date-info">{{ $video_article->display_created_at }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="more-media more-videos">
                            <a>{{ __('Смотреть все видео') }}</a>
                        </div>
                    </div>
                    <div class="photo">
                        <div class="photoblock-title">
                            <h3>{{ __('Фото') }}</h3>
                        </div>
                        <div class="photo-blocks">
                            @if($photo_articles)
                                @foreach($photo_articles as $photo_article)
                                    <a class="photo-block-link" href="{{ route('post-single', $photo_article->slug) }}">
                                        <div class="photo-block">
                                            <div class="media-capture">
                                                <img src="{{ $photo_article->file->full_preview_path }}"></img>
                                            </div>
                                            <h3 class="media-title">{{ $photo_article->title_short }}</h3>
                                            <span class="date-info">{{ $photo_article->display_created_at }}</span>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                        <div class="more-media more-pgotos">
                            <a>{{ __('Смотреть все фото') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="media second-media">
                <div class="photo">
                    <div class="photoblock-title">
                        <h3>{{ __('Видео') }}</h3>
                    </div>
                    <div class="photo-blocks">
                        @if($video_articles)
                            @foreach($video_articles as $video_article)
                                <div class="photo-block">
                                    <div class="media-capture">
                                        <div class="bg-video-icon-container" @if($video_article->file) data-video-path="{{ $video_article->file->full_path }}" @endif>
                                            <div class="bg-video-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                    <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/>
                                                </svg>
                                            </div>
                                            @if($video_article->thumb)
                                                <img src="{{ $video_article->thumb->full_path }}" alt="">
                                            @endif
                                        </div>
                                    </div>
                                    <h3 class="media-title"><a href="{{ route('post-single', $video_article->slug) }}">{{ $video_article->title_short }}</a></h3>
                                    <span class="date-info">{{ $video_article->display_created_at }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="more-media more-videos">
                        <a>{{ __('Смотреть все видео') }}</a>
                    </div>
                </div>
                <div class="photo">
                    <div class="photoblock-title">
                        <h3>{{ __('Фото') }}</h3>
                    </div>
                    <div class="photo-blocks">
                        @if($photo_articles)
                            @foreach($photo_articles as $photo_article)
                                <a href="{{ route('post-single', $photo_article->slug) }}">
                                    <div class="photo-block">
                                        <div class="media-capture">
                                            <img src="{{ $photo_article->file->full_preview_path }}"></img>
                                        </div>
                                        <h3 class="media-title">{{ $photo_article->title_short }}</h3>
                                        <span class="date-info">{{ $photo_article->display_created_at }}</span>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <div class="more-media more-pgotos">
                        <a>{{ __('Смотреть все фото') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="media-2">
        <div class="photo">
            <div class="photoblock-title">
                <h3>{{ __('Видео') }}</h3>
            </div>
            <div class="photo-blocks">
                @if($video_articles)
                    @foreach($video_articles as $video_article)
                        <div class="video-block">
                            <div class="media-capture">
                                <div class="bg-video-icon-container" @if($video_article->file) data-video-path="{{ $video_article->file->full_path }}" @endif>
                                    <div class="bg-video-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                            <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                            <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/>
                                        </svg>
                                    </div>
                                    @if($video_article->thumb)
                                        <img src="{{ $video_article->thumb->full_path }}" alt="">
                                    @endif
                                </div>
                            </div>
                            <h3 class="media-title"><a href="{{ route('post-single', $video_article->slug) }}">{{ $photo_article->title_short }}</a></h3>
                            <span class="date-info">{{ $video_article->display_created_at }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="more-media more-videos">
                <a>{{ __('Смотреть все видео') }}</a>
            </div>
        </div>
        <div class="photo">
            <div class="photoblock-title">
                <h3>{{ __('Фото') }}</h3>
            </div>
            <div class="photo-blocks">
                @if($photo_articles)
                    @foreach($photo_articles as $photo_article)
                        <div class="photo-block">
                            <div class="media-capture">
                                <img src="{{ $photo_article->file->full_preview_path }}"></img>
                            </div>
                            <h3 class="media-title"><a href="{{ route('post-single', $photo_article->slug) }}">{{ $photo_article->title_short }}</a></h3>
                            <span class="date-info">{{ $photo_article->display_created_at }}</span>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="more-media more-pgotos">
                <a>{{ __('Смотреть все фото') }}</a>
            </div>
        </div>
    </div>

    <div class="soon">
        <h2 class="soon-title">{{ __('Не пропустите') }}</h2>
        <div class="soon-materials">
            @if($materials_popular)
                @foreach($materials_popular as $material_popular)
                    <a class="soon-material-link" href="{{ route('material-single', $material_popular->slug) }}">
                        <div class="soon-material">
                            <div class="soon-block">
                                @if($material_popular->file)
                                    @if($material_popular->file->type === 'video')
                                        <div class="bg-video-icon-container" data-video-path="{{ $material_popular->file->full_path }}">
                                            <div class="bg-video-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                                    <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/>
                                                </svg>
                                            </div>
                                            @if($material_popular->thumb)
                                                <img src="{{ $material_popular->thumb->full_path }}" alt="">
                                            @endif
                                        </div>
                                    @else
                                        <img src="{{ $material_popular->file->full_preview_path }}" alt="">
                                    @endif
                                @endif
                                <h3 class="media-title">{{ $material_popular->subtitle }}</h3>
                                <p>{{ $material_popular->title_short }}</p>
                                <span class="date-info">{{ $material_popular->display_created_at }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</div>
<div class="container subscribe-banner-container">
    <div class="subscribe-banner">
        <div class="container">
            <div class="text">
                <h2>{{ __('Подпишитесь на электронную газету') }}</h2>
                <p>{{ __('(PDF) версия еженедельной общенациональной газеты «Сердало» и получайте свежие номера не выходя из дома!') }}</p>
                <button><a href="vk.com">{{ __('Подписаться') }}</a></button>
            </div>
            <img src="{{ asset('frontend/img/image 7.png') }}" alt="">
        </div>
    </div>
</div>
<!--

<div class="container" >
    <div class="museum">
        <h3 class="soon-title">Музей</h3>
        <div class="museum-cards">
            <div class="museum-card">
                <div class="card-image">
                    <img src="{{ asset('frontend/img/24b467d1991c0d1c3367aa22cd3caf5b.jpg') }}" alt="">
                </div>
                <div class="card-info">
                    <h3>За помощью и поддержкой</h3>
                    <p>Депутат Госдумы Бекхан Барахоев в своем телеграм-канале подвел итоги приема граждан за полгода</p>
                    <span>15 июля 2022, 01:12</span>
                </div>
            </div>
            <div class="museum-card">
                <div class="card-image">
                    <img src="{{ asset('frontend/img/24b467d1991c0d1c3367aa22cd3caf5b.jpg') }}" alt="">
                </div>
                <div class="card-info">
                    <h3>За помощью и поддержкой</h3>
                    <p>Депутат Госдумы Бекхан Барахоев в своем телеграм-канале подвел итоги приема граждан за полгода</p>
                    <span>15 июля 2022, 01:15</span>
                </div>
            </div>
        </div>
    </div>
    <div class="video-studio">
        <h3 class="soon-title">Видеостудия “Сердало”</h3>
        <div class="video-content">
            <div class="video-stud-block" autoplay>
                <video width="416" height="204" controls="" poster="video/duel.jpg" preload="none">
                    <source src="video/duel.ogv" type='video/ogg; codecs="theora, vorbis"'>
                    <source src="video/duel.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                    <source src="video/duel.webm" type='video/webm; codecs="vp8, vorbis"'>
                    Тег video не поддерживается вашим браузером.
                    <a href="video/duel.mp4">Скачайте видео</a>.
                </video>
                <h3 class="media-title">Отдых по принципу не навреди</h3>
                <span class="date-info">12 июля 2022, 15:22</span>
            </div>
            <div class="video-stud-block">
                <video width="416" height="204" controls="" preload="none">
                    <source src="video/duel.ogv" type='video/ogg; codecs="theora, vorbis"'>
                    <source src="video/duel.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                    <source src="video/duel.webm" type='video/webm; codecs="vp8, vorbis"'>
                    Тег video не поддерживается вашим браузером.
                    <a href="video/duel.mp4">Скачайте видео</a>.
                </video>
                <h3 class="media-title">Отдых по принципу не навреди</h3>
                <span class="date-info">12 июля 2022, 15:22</span>
            </div>
            <div class="video-stud-block">
                <video width="416" height="204" controls="" preload="none">
                    <source src="video/duel.ogv" type='video/ogg; codecs="theora, vorbis"'>
                    <source src="video/duel.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
                    <source src="video/duel.webm" type='video/webm; codecs="vp8, vorbis"'>
                    Тег video не поддерживается вашим браузером.
                    <a href="video/duel.mp4">Скачайте видео</a>.
                </video>
                <h3 class="media-title">Отдых по принципу не навреди</h3>
                <span class="date-info">12 июля 2022, 15:22</span>
            </div>
        </div>
    </div>
    <div class="lit-salon">
        <h3 class="soon-title">Литературный салон</h3>
        <div class="salon-blocks">
            <div class="salon">
                <div class="salon-img">
                    <img src="{{ asset('frontend/img/chahk.jpg') }}" alt="">
                </div>
                <div class="salon-info">
                    <h3>Чахкиев Саид Идрисович</h3>
                    <p>Народный писатель Чечено-Ингушетии</p>
                    <span>1938-2008гг.</span>
                </div>
            </div>
            <div class="salon">
                <div class="salon-img">
                    <img src="{{ asset('frontend/img/chahk.jpg') }}" alt="">
                </div>
                <div class="salon-info">
                    <h3>Чахкиев Саид Идрисович</h3>
                    <p>Народный писатель Чечено-Ингушетии</p>
                    <span>1938-2008гг.</span>
                </div>
            </div>
            <div class="salon">
                <div class="salon-img">
                    <img src="{{ asset('frontend/img/chahk.jpg') }}" alt="">
                </div>
                <div class="salon-info">
                    <h3>Чахкиев Саид Идрисович</h3>
                    <p>Народный писатель Чечено-Ингушетии</p>
                    <span>1938-2008гг.</span>
                </div>
            </div>
        </div>
    </div>
</div>

--!>
@endsection
