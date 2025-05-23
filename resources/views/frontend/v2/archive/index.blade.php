@extends('frontend/layouts/default')

@section('content')
    <div class="archive-page">
        <div class="container">
            <h2>Архив газеты</h2>
            <div class="archive-filter">
                <form method="get" action="{{ route('archive-index') }}">
                    <select name="years" id="years">
                        <option value="">Год</option>
                        @for($i = now()->year; $i > 1970; $i--)
                        <option value="{{ $i }}" @if(request('years') && request('years') == $i) selected @endif>{{ $i }}</option>
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
                    <input type="submit" value="Применить">
                    <button type="button" onclick="location.href='{{ route('archive-index') }}';">Сбросить</button>
                </form>
            </div>
            <div class="archive-items">
                @foreach($newspapers as $newspaper)
                <div class="archive-item">
                    <div class="archive-newspaper">
                        @if($newspaper->file && $newspaper->file->path)
                            @php
                                $newspaper_thumb_path = str_replace('/sites/default/files/gazette-pdf/', '', $newspaper->file->path);
                                $newspaper_thumb_path = 'https://serdalo.ru/sites/default/files/styles/max_650x650/public/pdfpreview/' . $newspaper->file->source_id . '-' . $newspaper_thumb_path;
                                $newspaper_thumb_path = str_replace('.pdf', '.png', $newspaper_thumb_path);
                            @endphp
                            <img src="{{ $newspaper_thumb_path }}" alt="">
                        @endif
                        <div class="item-info">

                            @php
                                $newspaper_file_path = '#';
                                if($newspaper->file && $newspaper->file->path) {
                                    $newspaper_file_path = str_replace('/sites/default/files/gazette-pdf/', '', $newspaper->file->path);
                                    $newspaper_file_path = 'https://serdalo.ru/sites/default/files/styles/max_650x650/public/pdfpreview/' . $newspaper->file->source_id . '-' . $newspaper_file_path;
                                }
                            @endphp
                            <p><span><a href="https://serdalo.ru/newspaper-subscribe/get-pdf/{{ $newspaper->source_id }}" target="_blank">{{ $newspaper->title }}</a></span></p>
                            <p><span><a href="https://serdalo.ru/newspaper-subscribe/get-pdf/{{ $newspaper->source_id }}" target="_blank">{{ $newspaper->release_at->format('d.m.Y') }}</a></span></p>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="pagination-container">
                {{ $newspapers->links() }}
            </div>
        </div>
    </div>
@endsection
