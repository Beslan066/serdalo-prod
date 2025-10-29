<!-- subscribe2 -->
<div class="subscribe2_wraper pt-8 pb-12">
    <div class="cm-min-h-52 p-5 bg-7">
        <div class="flex flex-row justify-between">
            <div class="md:basis-3/5">
                <p class="mb-2.5 text-xl md:text-2xl lg:text-4xl font-bold color-1">{{ __('Подпишитесь на электронную газету') }}</p>
                <p class="mb-5 lg:text-xl color-1">
                    {{ __('(PDF) версия еженедельной общенациональной газеты «Сердало» и получайте свежие номера не выходя из дома!') }}
                </p>
                <h5>                <a class="cm-button cm-button-7 w-full md:w-auto justify-center md:justify-start bg-1 color-2" href="{{route('payment-page')}}">{{ __('Подписаться') }}</a>
                </h5>
            </div>
            <div class="basis-2/5 relative hidden md:flex justify-end">
                <img class="subscribe2-image absolute h-72" src="{{ asset('frontend/v3/assets/media/base-v2/image-7.webp') }}" alt="Подпишитесь на газету">
            </div>
        </div>
    </div>
</div>
