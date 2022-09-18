<div class="block h-full w-full space-y-4 bg-white py-4">
    <h2 class="px-5 text-2xl font-bold">
        {{ __('Thank you for your interest in our tours!') }}
    </h2>

    <div class="px-5">
        <p class="text-gray-600">
            {{ __('Please contact us with social medias below and we will get back to you as soon as possible.') }}
        </p>

        <div class="mt-4 flex flex-col justify-center space-y-2">
            @if ($facebook_enable)
                <a href="{{ $facebook_link }}"
                    class="inline-flex items-center space-x-2 rounded bg-[#1877F2] px-4 py-2 font-semibold text-white">
                    <x-bi-facebook class="h-5 w-5 fill-current" />
                    <span>{{ __('Facebook') }}</span>
                </a>
            @endif

            @if ($twitter_enable)
                <a href="{{ $twitter_link }}"
                    class="inline-flex items-center space-x-2 rounded bg-[#1DA1F2] px-4 py-2 font-semibold text-white">
                    <x-bi-twitter class="h-5 w-5 fill-current" />
                    <span>{{ __('Twitter') }}</span>
                </a>
            @endif

            @if ($whatsapp_enable)
                <a href="{{ $whatsapp_link }}"
                    class="inline-flex items-center space-x-2 rounded bg-[#25D366] px-4 py-2 font-semibold text-white">
                    <x-bi-whatsapp class="h-5 w-5 fill-current" />
                    <span>{{ __('WhatsApp') }}</span>
                </a>
            @endif

            @if ($instagram_enable)
                <a href="{{ $instagram_link }}"
                    class="inline-flex items-center space-x-2 rounded bg-[#E4405F] px-4 py-2 font-semibold text-white">
                    <x-bi-instagram class="h-5 w-5 fill-current" />
                    <span>{{ __('Instagram') }}</span>
                </a>
            @endif
        </div>
    </div>

</div>
