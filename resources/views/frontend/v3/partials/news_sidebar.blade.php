<!-- sidebar Новости -->
<div class="cm-news_sidebar-wrapper col-span-10 sm:col-span-3 lg:col-span-2 xl:col-span-2 xl:pb-5">
    <!-- <img src="{{asset('80logo.jpg')}}" alt="" class="victoryImg">
    <div class="victory-block">
        <p>80 ПОБЕДА!</p>
    </div> -->
    <div class="cm-news_sidebar flex flex-col gap-3 bg-1 xl:py-2.5">
        @foreach($posts_main as $post_main)
            <div class="cm-news_sidebar-item font-medium px-2.5 pb-2.5 border-b border-color-3 last:border-b-0">
                <div  style="display: flex; justify-content: space-between;" class="news-date-mobile">
                    @if(\Carbon\Carbon::parse($post_main->published_at)->isToday())
                        <div class="flex justify-between">
                        <span class="block mb-1.5 cm-article-date font-bold color-7">
                            {{ \Carbon\Carbon::parse($post_main->published_at)->format('H:i')}}
                        </span>

                        </div>
                    @else
                        <span class="block mb-1.5 cm-article-date font-bold color-7">
                        {{ \Carbon\Carbon::parse($post_main->published_at)->format('d.m.Y H:i')}}
                    </span>
                    @endif
                    @if($post_main->topNews === 1)
                        <div class="topNews-icon">
                        </div>
                    @endif
                </div>
                @if($post_main->promote_with_file && $post_main->file)
                    <div class="relative">
                        @include('frontend.v3.partials.article_media', [
                                                    'model' => $post_main,
                                                    'link' => route('post-single', $post_main->slug),
                                                    'icon_align' => 'justify-center items-start',
                                                    'classes' => 'cm-aspect-16/9 sm:cm-aspect-3/4 md:cm-aspect-4/3 xl:cm-aspect-16/9'
                                                ])
                        <div class="absolute left-0 bottom-0 w-full h-full flex items-end pointer-events-none">
                            <div class="flex flex-col justify-end w-full min-h-1/2 p-2.5 color-1 cm-bd-gradient-1">
                                <a href="{{ route('post-single', $post_main->slug) }}"
                                   class="cm-article-subtitle pointer-events-auto">{{ $post_main->title_short }}</a>
                            </div>
                        </div>

                    </div>
                @else
                    <a href="{{ route('post-single', $post_main->slug) }}"
                       class="block cm-article-subtitle color-2">{{ $post_main->title_short }}</a>
                @endif
            </div>
        @endforeach
    </div>
</div>
