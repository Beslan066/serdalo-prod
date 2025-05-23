@extends('frontend/v3/layouts/default')

@section('content')
    <div class="content_wrapper grow">
        <main class="max-w-7xl mx-auto">

            <div class="cm-section-6 salon-work-bio py-5 flex flex-row">
                <div class="flex flex-col mb-5">
                    <div class="salon-person-avatar">
                        <img src="{{ asset(Storage::url($work->file->path )) }}" alt="">
                    </div>

                </div>

                <div class="flex flex-col">

                    <div class="salon-person-text">
                        <div class="person-name">
                            <h3>{{$work->title}}</h3>
                        </div>
                        <div class="person-description">
                            <p>
                                {{$work->description}}
                            </p>
                        </div>
                        <div class="person-years">
                            <span>{{$work->subtitle}}</span>
                        </div>
                    </div>
                    <div class="person-bio-awards flex flex-row">
                        <div class="biography work-text">
                            <div>
                                {!!$work->text_body!!}
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </main>
    </div>
@endsection
