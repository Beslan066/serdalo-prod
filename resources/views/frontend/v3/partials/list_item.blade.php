@if( in_array($loop->iteration, [1, 10]) )
    <div class="col-span-12 sm:col-span-12 xl:col-span-6 row-span-2 flex flex-col sm:flex-row h-full bg-1">
        <div class="basis-3/5 lg:basis-2/5 xl:basis-1/2 w-full h-full bg-5 overflow-hidden">
            @include('frontend.v3.partials.article_media', [
                'model' => $item,
                'link' => $link,
                'icon_size' => 'w-20 h-20',
                'classes' => 'cm-aspect-16/9 sm:cm-aspect-3/4 md:cm-aspect-4/3'
            ])
        </div>
        <div class="flex-1 basis-2/5 lg:basis-3/5 xl:basis-1/2 flex flex-col justify-between p-5">
            <div class="flex flex-col mb-3.5">
                <h3><a href="{{ $link }}" class="cm-article-title">{{ $item_title }}</a></h3>
                @if($item_subtitle)
                    <h4><span class="cm-article-subtitle">{{ $item_subtitle }}</span></h4>
                @endif
            </div>
            <div class="">
                @if(\Carbon\Carbon::parse($item->published_at)->isToday())
                    <span class="cm-article-date">
                        {{ \Carbon\Carbon::parse($item->published_at)->format('H:i')}}
                    </span>
                @else
                    <span class="cm-article-date">
                        {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y H:i')}}
                    </span>
                @endif

            </div>
        </div>
    </div>
@elseif( in_array($loop->iteration, [2, 3, 8, 9]) )
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 row-span-2 flex flex-col lg:flex-row xl:flex-col h-full bg-1">
        <div class="basis-1/2 md:basis-3/5 lg:basis-2/5 xl:basis-1/2 w-full bg-5 overflow-hidden">
            @include('frontend.v3.partials.article_media', [
                'model' => $item,
                'link' => $link,
                'icon_size' => 'w-16 h-16',
                'classes' => 'cm-aspect-16/9 lg:cm-aspect-4/3 xl:cm-aspect-4/3'
            ])
        </div>
        <div class="flex-1 basis-1/2 md:basis-2/5 lg:basis-3/5 xl:basis-1/2 flex flex-col justify-between p-5">
            <div class="flex flex-col mb-3.5">
                <h3><a href="{{ $link }}" class="cm-article-title">{{ $item_title }}</a></h3>
                @if($item_subtitle)
                    <h4><span class="cm-article-subtitle">{{ $item_subtitle }}</span></h4>
                @endif
            </div>
            <div class="">
                @if(\Carbon\Carbon::parse($item->published_at)->isToday())
                    <span class="cm-article-date">
                        {{ \Carbon\Carbon::parse($item->published_at)->format('H:i')}}
                    </span>
                @else
                    <span class="cm-article-date">
                        {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y H:i')}}
                    </span>
                @endif
            </div>
        </div>
    </div>
@else
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 row-span-1">
        <div class="flex flex-col h-full justify-between bg-1 p-5">
            <div class="flex flex-col mb-3.5">
                <h3><a href="{{ $link }}" class="cm-article-title">{{ $item_title }}</a></h3>
                @if($item_subtitle)
                    <h4><span class="cm-article-subtitle">{{ $item_subtitle }}</span></h4>
                @endif
            </div>
            <div class="">
                @if(\Carbon\Carbon::parse($item->published_at)->isToday())
                    <span class="cm-article-date">
                        {{ \Carbon\Carbon::parse($item->published_at)->format('H:i')}}
                    </span>
                @else
                    <span class="cm-article-date">
                        {{ \Carbon\Carbon::parse($item->published_at)->format('d.m.Y H:i')}}
                    </span>
                @endif

            </div>
        </div>
    </div>
@endif
