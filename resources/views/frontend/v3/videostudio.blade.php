@extends('frontend/v3/layouts/default')
@section('content')



    <main class="max-w-7xl mx-auto py-5 px-1.5 sm:px-5">

        @if(count($videoArticles))
            <div class="cm-section-5 py-5">
                @include('frontend.v3.partials.page_title', [
                    'title' => __('Видеостудия "Сердало"'),
                ])
                <div class="grid grid-cols-12 gap-5">
                    @foreach($videoArticles as $videoArticle)

                        <div class="cm-section-5-item col-span-12 md:col-span-6 lg:col-span-4 flex flex-col bg-1">
                            <a href="#" class="cm-article-title cursor-pointer">
                                @include('frontend.v3.partials.article_media', [
                                    'model' => $videoArticle,
                                    'link' => '#',
                                    'classes' => 'w-full aspect-video object-cover cursor-pointer'
                                ])
                            </a>
                            <div class="flex flex-col h-full justify-between p-5">
                                <div class="flex flex-col mb-5">
                                    <a href="#" class="cm-article-title">{{ $videoArticle->title }}</a>
                                </div>
                                <div class="">
                                    <span class="cm-article-date">{{ $videoArticle->display_created_at }}</span>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        @endif

    </main>
    



@endsection

