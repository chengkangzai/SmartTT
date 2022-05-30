@php
/** @var \App\Models\Settings\GeneralSetting $setting */
@endphp

<div class="p-4">
    <div class="container mx-auto">
        <h3 class="pb-10 text-center text-3xl font-extrabold">{{ __('Why book with us?') }}</h3>
    </div>
    <div class="container mx-auto grid gap-4 md:grid-cols-3">
        <div class="flex flex-col rounded-xl bg-amber-300 py-2 shadow-xl md:py-4">
            <div class="mx-4 flex h-12 w-12 items-center justify-center rounded-md bg-red-500 text-white">
                <svg class="inline h-5 w-5">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-beach-access') }}"></use>
                </svg>
            </div>
            <div class="flex-1 px-4 py-2 md:py-4">
                <h4 class="text-xl font-semibold">{{ __('Best Price Guarantee') }}</h4>
                <p class="my-2 text-gray-800">
                    {{ __('We guarantee you the best price for your trip') }}
                </p>
            </div>
        </div>
        <div class="flex flex-col rounded-xl bg-sky-300 py-2 shadow-xl md:py-4">
            <div class="mx-4 flex h-12 w-12 items-center justify-center rounded-md bg-blue-500 text-white">
                <svg class="inline h-5 w-5">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-map') }}"></use>
                </svg>
            </div>
            <div class="flex-1 p-4">
                <h4 class="text-xl font-semibold">{{ __('Best Location') }}</h4>
                <p class="my-2 text-gray-800">
                    {{ __('We have the best location for your trip') }}
                </p>
            </div>
        </div>

        <div class="flex flex-col rounded-xl bg-lime-300 py-2 shadow-xl md:py-4">
            <div class="mx-4 flex h-12 w-12 items-center justify-center rounded-md bg-emerald-500 text-white">
                <svg class="inline h-5 w-5">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-credit-card') }}"></use>
                </svg>
            </div>
            <div class="flex-1 p-4">
                <h4 class="text-xl font-semibold">{{ __('Trust & Safety') }}</h4>
                <p class="my-2 text-gray-800">
                    {{ __('payment is powered by stripe, recognise internationally') }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="flex flex-wrap justify-between md:flex-row md:flex-nowrap">
            <div class="mx-auto w-full border-b-2 p-5 md:border-none">
                <div class="flex flex-col">
                    <div class="flex items-center justify-center">
                        <img src="{{ asset('button_smart-tt.png') }}" alt="Site Logo" class="inline w-32">
                    </div>
                    <h3 class="py-4 text-center text-3xl font-bold">{{ $site_name }}</h3>
                    <p class="text-center text-gray-800">
                        {{ $setting->company_phone }}
                    </p>
                    <p class="text-center text-gray-800">
                        {{ env('MAIL_FROM_ADDRESS') }}
                    </p>
                    <p class="text-center text-gray-800">
                        {!! str($setting->company_address)->replace(',', ', <br>') !!}
                    </p>
                </div>
            </div>
            <div class="mx-8 hidden border md:flex"></div>
            <div class="w-full grow border-b-2 p-5 md:border-none">
                <h6 class="py-2 text-center text-2xl font-bold md:py-4">{{ __('Quick Links') }}</h6>
                <div class="mx-auto flex flex-row justify-around gap-8 py-4 md:py-8">
                    <div class="flex flex-col">
                        <p class="text-xl font-semibold">{{ __('Resources') }}</p>
                        <a href="{{ route('home') }}">{{ __('Home') }}</a>
                        <a href="{{ route('home') }}">{{ __('Home') }}</a>
                        <a href="{{ route('home') }}">{{ __('Home') }}</a>
                    </div>
                    <div class="flex flex-col">
                        <p class="text-xl font-semibold">{{ __('Resources') }}</p>
                        <a href="{{ route('home') }}">{{ __('Home') }}</a>
                        <a href="{{ route('home') }}">{{ __('Home') }}</a>
                        <a href="{{ route('home') }}">{{ __('Home') }}</a>
                    </div>
                </div>
            </div>
            <div class="hidden border md:mx-8 md:flex"></div>
            <div class="mx-auto w-full p-5">
                <h6 class="py-4 text-center text-2xl font-bold">{{ __('Supported Payments') }}</h6>
                <div class="mx-auto flex flex-row justify-between gap-8 md:py-8">
                    <svg class="h-8 w-8">
                        <use xlink:href="{{ asset('icons/brand.svg#cib-cc-stripe') }}"></use>
                    </svg>
                    <svg class="h-8 w-8">
                        <use xlink:href="{{ asset('icons/brand.svg#cib-cc-amex') }}"></use>
                    </svg>
                    <svg class="h-8 w-8">
                        <use xlink:href="{{ asset('icons/brand.svg#cib-cc-mastercard') }}"></use>
                    </svg>
                    <svg class="h-8 w-8">
                        <use xlink:href="{{ asset('icons/brand.svg#cib-cc-visa') }}"></use>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="border-t bg-gray-100">
    <p class="p-2 text-center">
        &copy; {{ __('Power by SmartTT') }}
    </p>
</div>