@php
    $container_classes = $container_classes ?? '';
    $icon_align = $icon_align ?? 'justify-center items-center';
    $icon_size = $icon_size ?? 'w-14 h-14';
    $icon_bg = $icon_bg ?? 'bg-7';
    $no_img_bg = $no_img_bg ?? 'bg-5';

    // ОПРЕДЕЛЯЕМ URL ДЛЯ ИЗОБРАЖЕНИЯ - ВСЕГДА WEBP ЕСЛИ ЕСТЬ
    $imageUrl = null;
    $thumbUrl = null;

    if($model->file && $model->file->type === 'image') {
        $originalPath = $model->file->path;
        $webpPath = pathinfo($originalPath, PATHINFO_DIRNAME) . '/' .
                   pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';

        if(Storage::disk('public')->exists($webpPath)) {
            $imageUrl = Storage::disk('public')->url($webpPath);
        } else {
            $imageUrl = $model->file->full_preview_path ?? $model->file->full_path;
        }
    }

    if($model->thumb && $model->thumb->type === 'image') {
        $originalPath = $model->thumb->path;
        $webpPath = pathinfo($originalPath, PATHINFO_DIRNAME) . '/' .
                   pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';

        if(Storage::disk('public')->exists($webpPath)) {
            $thumbUrl = Storage::disk('public')->url($webpPath);
        } else {
            $thumbUrl = $model->thumb->full_path;
        }
    }
@endphp

@if($model->file)
    @if($model->file->type === 'video')
        <div class="cm-article-video-container w-full h-full {{ $container_classes }} relative js--open-video-modal" data-link="{{ $link ?? '' }}" data-title="{{ $model->title_short }}" data-src="{{ $model->file->full_path }}">
            <div class="w-full h-full {{ $classes }} {{ $no_img_bg }}">
                @if($model->thumb && $thumbUrl)
                    <img class="w-full h-full {{ $classes }} object-cover"
                         src="{{ $thumbUrl }}"
                         alt="Новости Ингушетии: {{$model->title}}" fetchpriority=high>
                @endif
            </div>
            <div class="absolute left-0 bottom-0 w-full h-full flex {{ $icon_align }} p-2.5 color-1 poiner-events-none">
                <a href="#" class="{{ $icon_size }} flex justify-center items-center rounded-full poiner-events-auto play-icon-link">
                    <img class="play-icon" src="{{ asset('frontend/v3/assets/media/base-v2/play.png') }}" alt="Новости Ингушетии">
                </a>
            </div>
        </div>
    @else
        <div class="w-full h-full {{ $classes }} ">
            @if($imageUrl)
                <img class="w-full h-full {{ $classes }} object-cover sticky-image"
                     src="{{ $imageUrl }}"
                     alt="Новости Ингушетии: {{$model->title}}" fetchpriority=high>
            @endif
        </div>
    @endif
@endif
