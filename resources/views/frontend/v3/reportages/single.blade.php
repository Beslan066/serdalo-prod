@extends('frontend/v3/layouts/default')
@section('content')


    <div class="cm-single-content container p-5 md:pr-10 lg:pr-20 bg-1">

        <p class="mb-1 text-base font-semibold">{{$reportage->title}}</p>
        <div class="flex flex-col sm:flex-row gap-2.5 sm:gap-5 mb-2.5">
            <div class="">

                <span class="inline-block flex-none font-semibold color-7 border-b border-color-7">{{ \Carbon\Carbon::parse($reportage->published_at)->translatedFormat('j F Y, H:i')}}</span>
            </div>
        </div>
        <div class="cm-content-media mb-5">
            <div id="app" class=" mx-auto px-4 md:px-8 transition-all duration-500 ease-linear">
                <div class="relative">
                    <div class="slides-container flex snap-x snap-mandatory overflow-hidden overflow-x-auto space-x-2 rounded scroll-smooth before:w-[45vw] before:shrink-0 after:w-[45vw] after:shrink-0 md:before:w-0 md:after:w-0">
                        @foreach($photos as $photo)
                            <div class="slide aspect-square h-full flex-shrink-0 snap-center rounded overflow-hidden reportage-slide-item">
                                {!! $photo !!}
                            </div>
                        @endforeach


                    </div>

                    <div class="absolute top-0 -left-4 h-full items-center hidden md:flex">
                        <button role="button" class="prev px-2 py-2 rounded-full bg-neutral-100 text-neutral-900 group" aria-label="prev"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-active:-translate-x-2 transition-all duration-200 ease-linear">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>

                        </button>
                    </div>
                    <div class="absolute top-0 -right-4 h-full items-center hidden md:flex">
                        <button role="button" class="next px-2 py-2 rounded-full bg-neutral-100 text-neutral-900 group" aria-label="next"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-active:translate-x-2 transition-all duration-200 ease-linear">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <div class="single-text-center">
            <div>
                <p class="mb-10 text-base font-semibold">{{$reportage->lead}}</p>
            </div>
            <div class="cm-single-description text-base">

            </div>
        </div>
    </div>



@endsection


