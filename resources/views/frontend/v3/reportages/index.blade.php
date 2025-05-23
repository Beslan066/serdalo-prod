@extends('frontend/v3/layouts/default')
@section('content')



    <main class="max-w-7xl mx-auto py-5 px-1.5 sm:px-5">

        @if(count($reportages))
            <div class="cm-section-5 py-5">
                @include('frontend.v3.partials.page_title', [
                    'title' => __('Фоторепортажи'),
                ])
                <div class="grid grid-cols-12 gap-5">
                    @foreach($reportages as $reportage)

                        <div class="cm-section-5-item col-span-12 md:col-span-6 lg:col-span-4 flex flex-col bg-1">
                            <a href="#" class="cm-article-title cursor-pointer">
                                @include('frontend.v3.partials.article_media', [
                                    'model' => $reportage,
                                    'link' => route('reportage-single', $reportage->id),
                                    'classes' => 'w-full aspect-video object-cover cursor-pointer'
                                ])
                            </a>
                            <div class="flex flex-col h-full justify-between p-5">
                                <div class="flex flex-col mb-5">
                                    <a href="{{route('reportage-single', $reportage->id)}}" class="cm-article-title">{{ $reportage->title }}</a>
                                </div>
                                <div class="">
                                    @if(\Carbon\Carbon::parse($reportage->published_at)->isToday())
                                        <span class="cm-article-date">
                                             {{ \Carbon\Carbon::parse($reportage->published_at)->format('H:i')}}
                                        </span>
                                    @else
                                        <span class="cm-article-date">
                                            {{ \Carbon\Carbon::parse($reportage->published_at)->format('d.m.Y H:i')}}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>
            </div>
        @endif

    </main>




@endsection

