<div class="block w-full h-full bg-white space-y-4 py-4">
    <h2 class="text-2xl px-5 font-bold">
        {{ __('Thank you for your interest in our tours!') }}
    </h2>

    <div class="px-5">
        <p class="text-gray-600">
            {{ __('Please contact us with social medias below and we will get back to you as soon as possible.') }}
        </p>

        <div class="flex flex-col justify-center space-y-2 mt-4">
            @if($facebook_enable)
                <a href="{{$facebook_link}}"
                   class="bg-[#1877F2] px-4 py-2 font-semibold text-white inline-flex items-center space-x-2 rounded">
                    <x-bi-facebook class="w-5 h-5 fill-current"/>
                    <span>{{__('Facebook')}}</span>
                </a>
            @endif

            @if($twitter_enable)
                <a href="{{$twitter_link}}"
                   class="bg-[#1DA1F2] px-4 py-2 font-semibold text-white inline-flex items-center space-x-2 rounded">
                    <x-bi-twitter class="w-5 h-5 fill-current"/>
                    <span>{{__('Twitter')}}</span>
                </a>
            @endif

            @if($whatsapp_enable)
                <a href="{{$whatsapp_link}}"
                   class="bg-[#25D366] px-4 py-2 font-semibold text-white inline-flex items-center space-x-2 rounded">
                    <x-bi-whatsapp class="w-5 h-5 fill-current"/>
                    <span>{{__('WhatsApp')}}</span>
                </a>
            @endif

            @if($instagram_enable)
                <a href="{{$instagram_link}}"
                    class="bg-[#E4405F] px-4 py-2 font-semibold text-white inline-flex items-center space-x-2 rounded">
                    <x-bi-instagram class="w-5 h-5 fill-current"/>
                    <span>{{__('Instagram')}}</span>
                </a>
            @endif
        </div>
    </div>

</div>
