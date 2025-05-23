@if( in_array($loop->iteration, [1, 10]) )
    <div class="material-image-block">
        <div class="material-image">
            @if($item->file)
                @if($item->file->type === 'video')
                    <div class="bg-video-icon-container" data-video-path="{{ $item->file->full_path }}">
                        <div class="bg-video-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/>
                            </svg>
                        </div>
                        @if($item->thumb)
                            <img src="{{ $item->thumb->full_path }}" alt="">
                        @endif
                    </div>
                @else
                    <img class="material-image-photo" src="{{ $item->file->full_preview_path }}" alt="">
                @endif
            @endif
        </div>
        <div class="material-content">
            <a href="{{ $link }}" class="material-link">
                <h3>{{ $item_title }}</h3>
                @if($item_subtitle)<p>{{ TextHelper::short($item_subtitle, 120) }}</p>@endif
            </a>
            <span class="date-info">{{ $item->created_at->translatedFormat('j F Y, H:i') }}</span>
        </div>
    </div>
@elseif( in_array($loop->iteration, [2, 3, 8, 9]) )
    <div class="soon-material">
        <div class="soon-block">
            <div class="material-media-container">
                @if($item->file)
                    @if($item->file->type === 'video')
                        <div class="bg-video-icon-container" data-video-path="{{ $item->file->full_path }}">
                            <div class="bg-video-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                    <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                                    <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/>
                                </svg>
                            </div>
                            @if($item->thumb)
                                <img src="{{ $item->thumb->full_path }}" alt="">
                            @endif
                        </div>
                    @else
                        <img src="{{ $item->file->full_preview_path }}" alt="">
                    @endif
                @endif
            </div>
            <a href="{{ $link }}" class="material-link">
                <h3 class="media-title news-page-media-title">{{ $item_title }}</h3>
                @if($item_subtitle)<p>{{ TextHelper::short($item_subtitle, 120) }}</p>@endif
            </a>
            <span class="date-info">{{ $item->created_at->translatedFormat('j F Y, H:i') }}</span>
        </div>
    </div>
@else
    <div class="material">
        <a href="{{ $link }}" class="material-link">
            <h2 class="material-image-h2">{{ $item_title }}</h2>
            @if($item_subtitle)<p>{{ TextHelper::short($item_subtitle, 100) }}</p>@endif
        </a>
        <span class="date-info">{{ $item->created_at->translatedFormat('j F Y, H:i') }}</span>
    </div>
@endif
