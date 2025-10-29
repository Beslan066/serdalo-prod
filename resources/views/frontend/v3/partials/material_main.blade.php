@if($show_file && $model->promote_with_file && $model->file)
    <div class="{{ ($show_mobile) ? 'flex' : 'hidden xl:flex' }} col-span-2 xl:col-span-1 row-span-2 flex-col md:flex-row h-full bg-1">
        <div class="basis-1/2 md:basis-1/2 lg:basis-1/2 flex-1 w-full h-full">
            @include('frontend.v3.partials.article_media', [
                'model' => $model,
                'link' => route('material-single', $model->slug),
                'icon_size' => 'w-20 h-20 md:w-14 lg:h-14 md:w-20 lg:h-20',
                'classes' => 'cm-aspect-16/9 md:cm-aspect-4/3 lg:cm-aspect-16/9 xl:cm-aspect-3/4'
            ])
        </div>
        <div class="basis-1/2 md:basis-1/2 lg:basis-1/2 flex-1 flex flex-col justify-between p-5">
            <div class="flex flex-col mb-3.5">
                <h4><a href="{{ route('material-single', $model->slug) }}" class="cm-article-title">{{ $model->subtitle_short }}</a></h4>
                <h3><span class="cm-article-subtitle">{{ $model->title_short }}</span></h3>
            </div>

            <div class="">
                @if(\Carbon\Carbon::parse($model->published_at)->isToday())
                    <span class="cm-article-date">{{ \Carbon\Carbon::parse($model->published_at)->format('H:i')}}</span>
                @else
                    <span class="cm-article-date">{{ \Carbon\Carbon::parse($model->published_at)->format('d.m.Y H:i')}}</span>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="{{ ($show_mobile) ? 'block' : 'hidden xl:block' }} w-full col-span-2 xl:col-span-1 row-span-1">
        <div class="flex flex-col h-full justify-between bg-1 p-5">
            <div class="flex flex-col mb-3.5">
                <h4><a href="{{ route('material-single', $model->slug) }}" class="cm-article-title">{{ $model->subtitle_short }}</a></h4>
                <h3><span class="cm-article-subtitle">{{ $model->title_short }}</span></h3>
            </div>
            <div class="">
                <span class="cm-article-date">{{ \Carbon\Carbon::parse($model->published_at)->format('d.m.Y H:i')}}</span>
            </div>
        </div>
    </div>
@endif
