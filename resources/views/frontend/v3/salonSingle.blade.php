@extends('frontend/v3/layouts/default')

@section('content')
    <div class="content_wrapper grow">
        <main class="max-w-7xl mx-auto">


            <div class="cm-section-6 py-5 flex single-salon-content">
                <div class="flex flex-col mb-5">
                    <div class="salon-person-avatar">
                        <img src="{{ asset(Storage::url($article->file->path )) }}" alt="">
                    </div>

                    <div class="person-items">
                        @if($publications)

                            @foreach($publications as $item)
                                <a href="{{route('workSingle', $item->id)}}">
                                    <div class="lit-item">
                                        <img
                                            src="{{ asset(Storage::url($item->file->path )) }}"
                                            alt="">
                                        <div class="lit-item-text flex flex-row">
                                            <h4>{{$item->title}}</h4>
                                            <span>{{$item->subtitle}}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                        @endif

                    </div>
                </div>

                <div class="flex flex-col">

                    <div class="salon-person-text">
                        <div class="person-name">
                            <h3>{{$article->title}}</h3>
                        </div>
                        <div class="person-description">
                            <p>
                                Известный ингушский писатель, поэт, драматург, кинодраматург и баснописец, детский
                                писатель и переводчик, публицист и общественный деятель.
                                Народный писатель Чечено-Ингушетии, дипломант Всесоюзного литературного конкурса имени
                                Н. Островского, видный общественный деятель, член Союза писателей и Союза журналистов
                                России.
                            </p>
                        </div>
                        <div class="person-years">
                            <span>{{$article->dates}}</span>
                        </div>
                    </div>
                    <div class="person-bio-awards flex">
                        <div class="biography">
                            <h3>Биография</h3>
                            <div>
                                {!!$article->biography!!}
                            </div>
                        </div>
                        <div class="person-awards">
                            @if($awards)
                                @if($awards)
                                <h3>Награды</h3>
                                @endif
                                <div class="awards">
                                    @foreach($awards as $award)
                                        <div class="lit-item">
                                            @if(isset($award->file->path))
                                                <img
                                                    src="{{ asset(Storage::url($award->file->path )) }}"
                                                    alt="">
                                            @endif
                                            <div class="lit-item-text flex flex-row mt-5">
                                                <h4>{{$award->title}}</h4>
                                                <span>{{$award->subtitle}}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="about-сreativity-text">
                        <h3>О творчестве</h3>
                        <div class="flex flex-col about-сreativity">
                            {!! $article->about_creativity !!}
                        </div>

                    </div>
                </div>
            </div>

        </main>
    </div>
@endsection
