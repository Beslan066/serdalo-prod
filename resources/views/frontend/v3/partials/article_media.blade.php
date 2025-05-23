@php
    $container_classes = $container_classes ?? '';
    $icon_align = $icon_align ?? 'justify-center items-center';
    $icon_size = $icon_size ?? 'w-14 h-14';
    $icon_bg = $icon_bg ?? 'bg-7';
    $no_img_bg = $no_img_bg ?? 'bg-5';
@endphp
@if($model->file)
    @if($model->file->type === 'video')
        <div class="cm-article-video-container w-full h-full {{ $container_classes }} relative js--open-video-modal" data-link="{{ $link ?? '' }}" data-title="{{ $model->title_short }}" data-src="{{ $model->file->full_path }}">
            <div class="w-full h-full {{ $classes }} {{ $no_img_bg }}">
                @if($model->thumb)
                    <img class="w-full h-full {{ $classes }} object-cover" src="{{ $model->thumb->full_path }}" alt="Новости Ингушетии: {{$model->title}}">
                @endif
            </div>
            <div class="absolute left-0 bottom-0 w-full h-full flex {{ $icon_align }} p-2.5 color-1 poiner-events-none">
                <a href="#" class="{{ $icon_size }} flex justify-center items-center rounded-full  poiner-events-auto play-icon-link">
                    <img class="play-icon" src="{{ asset('frontend/v3/assets/media/base-v2/play.png') }}" alt="Новости Ингушетии">
                </a>
            </div>
        </div>
    @else
        <div class="w-full h-full {{ $classes }}">
            @if($model->file->full_preview_path)
                <img class="w-full h-full {{ $classes }} object-cover" src="{{ $model->file->full_preview_path }}" alt="Новости Ингушетии: {{$model->title}}">
            @else
                <img class="w-full h-full {{ $classes }} object-cover" src="{{ $model->file }}" alt="Новости Ингушетии: {{$model->title}}">
            @endif
        </div>
    @endif
@endif

