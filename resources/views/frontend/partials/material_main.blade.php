@if($show_file && $model->promote_with_file && $model->file)
    <a href="{{ route('material-single', $model->slug) }}" class="material-link">
    <div class="material-image-block">
        <div class="material-image">
            @if($model->file->type === 'video')
                <div class="bg-video-icon-container" data-video-path="{{ $model->file->full_path }}">
                    <div class="bg-video-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) -->
                            <path d="M424.4 214.7L72.4 6.6C43.8-10.3 0 6.1 0 47.9V464c0 37.5 40.7 60.1 72.4 41.3l352-208c31.4-18.5 31.5-64.1 0-82.6z"/>
                        </svg>
                    </div>
                    @if($model->thumb)
                        <img class="material-image-photo" src="{{ $model->thumb->full_path }}" alt="">
                    @endif
                </div>
            @else
                <img class="material-image-photo" src="{{ $model->file->full_preview_path }}" alt="">
            @endif
        </div>
        <div class="material-content">

                <h3>{{ $model->subtitle_short }}</h3>
                <p>{{ $model->title }}...</p>

            <span class="date-info">{{ $model->display_created_at }}</span>
        </div>
    </div>
    </a>
@else
    <div class="material">
      <h2><a href="{{ route('material-single', $model->slug) }}">{{ $model->subtitle_short }}</a></h2>
        <p>{{ $model->title }}...</p>
        <span>{{ $model->display_created_at }}</span>
    </div>
@endif
