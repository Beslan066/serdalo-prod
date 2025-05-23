@extends('frontend/v3/layouts/default')
@section('content')
<div class="">
    @include('frontend.v3.partials.page_title', [
        'title' => __('Архив газеты'),
    ])
    <h3 class="text-xl font-bold mb-5">{{ __('Поиск газеты') }}</h3>
    <div class="mb-5">
        <form class="flex flex-wrap gap-1.5 sm:gap-5" method="get" action="{{ route('archive-index') }}">
            <div class="flex flex-wrap gap-1.5 sm:gap-5">
                <select name="years" id="years">
                    <option value="">Год</option>
                    @for($i = now()->year; $i > 1970; $i--)
                        <option
                            value="{{ $i }}"
                            @if(request('years') && request('years') == $i) selected @endif
                        >{{ $i }}</option>
                    @endfor
                </select>
                <select name="months" id="months">
                    <option value="">Месяц</option>
                    <option value="1" @if(request('months') && request('months') == 1) selected @endif>{{ __('Январь') }}</option>
                    <option value="2" @if(request('months') && request('months') == 2) selected @endif>{{ __('Февраль') }}</option>
                    <option value="3" @if(request('months') && request('months') == 3) selected @endif>{{ __('Март') }}</option>
                    <option value="4" @if(request('months') && request('months') == 4) selected @endif>{{ __('Апрель') }}</option>
                    <option value="5" @if(request('months') && request('months') == 5) selected @endif>{{ __('Май') }}</option>
                    <option value="6" @if(request('months') && request('months') == 6) selected @endif>{{ __('Июнь') }}</option>
                    <option value="7" @if(request('months') && request('months') == 7) selected @endif>{{ __('Июль') }}</option>
                    <option value="8" @if(request('months') && request('months') == 8) selected @endif>{{ __('Август') }}</option>
                    <option value="9" @if(request('months') && request('months') == 9) selected @endif>{{ __('Сентябрь') }}</option>
                    <option value="10" @if(request('months') && request('months') == 10) selected @endif>{{ __('Октябрь') }}</option>
                    <option value="11" @if(request('months') && request('months') == 11) selected @endif>{{ __('Ноябрь') }}</option>
                    <option value="12" @if(request('months') && request('months') == 12) selected @endif>{{ __('Декабрь') }}</option>
                </select>
            </div>
            <div class="flex flex-wrap gap-1.5 sm:gap-5">
                <button class="cm-button active" type="submit">Применить</button>
                <button class="cm-button cm-button-1" type="button" onclick="location.href='{{ route('archive-index') }}';">Сбросить</button>
            </div>
        </form>
    </div>
    <div class="grid grid-cols-12 gap-5">
        @foreach($newspapers as $newspaper)
            @php
                $newspaper_file_path = '#';
                if($newspaper->file && $newspaper->file->path) {
                    $newspaper_file_path = str_replace('/sites/default/files/gazette-pdf/', '/storage', $newspaper->file->path);
                    $newspaper_file_path = 'https://serdalo.ru/sites/default/files/styles/max_650x650/public/pdfpreview/'
                        . $newspaper->file->source_id . '-' . $newspaper_file_path;
                }
            @endphp
            <div class="col-span-6 md:col-span-4 xl:col-span-3 cm-aspect-pdf bg-5">
                <div class="flex h-full font-semibold">
                    <div class="relative w-full h-full">
                        <div class="w-full h-full">
                            @if($newspaper->file && $newspaper->file->path)
                                @php
                                    $newspaper_thumb_path = str_replace('/sites/default/files/gazette-pdf/', '', $newspaper->file->path);
                                    $newspaper_thumb_path = 'https://serdalo.ru/sites/default/files/styles/max_650x650/public/pdfpreview/'
                                        . $newspaper->file->source_id . '-' . $newspaper_thumb_path;
                                    $newspaper_thumb_path = str_replace('.pdf', '.png', $newspaper_thumb_path);
                                @endphp
                                <img class="w-full h-full object-cover" src="{{ $newspaper_thumb_path }}">
                            @endif
                        </div>
                        <div class="absolute left-0 bottom-0 w-full h-full flex items-end">

                            <a
                                class="flex flex-col justify-end gap-2.5 w-full min-h-1/2 p-5 cm-bd-gradient-7"
                                href="https://serdalo.ru/storage/{{ $newspaper->file->path }}"
                                target="_blank"
                            >
                                <span class="text-xs sm:text-base archive-title">{{ $newspaper->title }}</span>
                                <span class="text-xs sm:text-base lg:text-xl color-1">{{ $newspaper->release_at->format('d.m.Y') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @include('frontend.v3.partials.pagination_container', [
        'models' => $newspapers,
    ])
</div>

<div class="py-5">
    @include('frontend.v3.partials.subscribe_lead_2')
</div>

@endsection
