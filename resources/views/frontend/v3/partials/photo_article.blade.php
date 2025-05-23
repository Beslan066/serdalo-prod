@php
    $container_classes = $container_classes ?? '';
    $icon_align = $icon_align ?? 'justify-center items-center';
    $icon_size = $icon_size ?? 'w-14 h-14';
    $icon_bg = $icon_bg ?? 'bg-7';
    $no_img_bg = $no_img_bg ?? 'bg-5';
@endphp

        <div class="w-full h-full {{ $classes }}">
            <img class="w-full h-full {{ $classes }} object-cover" src="{{ $model->file->full_preview_path }}" alt="Новости Ингушетии: {{$model->title}}">
        </div>

