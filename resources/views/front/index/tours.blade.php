@php
    /** @var \App\Models\Tour $tour */
@endphp

@extends('front.layouts.app')
@section('title')
    {{ $tour->name }}
@endsection

@section('content')
    <div class="grid min-h-[50vh] w-full place-items-center bg-cover bg-center bg-no-repeat opacity-90 md:min-h-[60vh] md:py-40"
        style="background-image: url('{{ $tour->getFirstMediaUrl('thumbnail') }}');">
    </div>
    <div class="container mx-auto flex flex-wrap">
        <div class="mt-4 flex h-fit grow justify-evenly gap-8 px-8 py-2 text-center md:justify-start md:px-0">
            <a target="_self" href="{{ request()->fullUrl() }}#detail"
                class="my-auto font-medium hover:text-gray-500 md:text-xl">
                {{ __('Details') }}
            </a>
            <a target="_self" href="{{ request()->fullUrl() }}#itinerary"
                class="my-auto font-medium hover:text-gray-500 md:text-xl">
                {{ __('Itinerary') }}
            </a>
            @if (app(\App\Models\Settings\GeneralSetting::class)->site_mode !== 'Enquiry')
                <a target="_self" href="{{ request()->fullUrl() }}#packages"
                    class="my-auto font-medium hover:text-gray-500 md:text-xl">
                    {{ __('Packages') }}
                </a>
            @endif
        </div>
        <livewire:front.index.tour.price-card :tour="$tour" />
    </div>
    <div id="detail" class="container mx-auto px-4 md:px-2 md:pt-10">
        <a target="_self" href="{{ request()->fullUrl() }}#detail"
            class="text-center text-2xl font-bold leading-relaxed md:text-4xl md:underline md:underline-offset-8">
            # {{ $tour->name }}
        </a>
        <div class="my-4">
            @foreach ($tour->countries as $country)
                <p class="inline-block w-fit rounded-xl bg-sky-100 px-2 py-1 text-sm">{{ $country->name }}</p>
            @endforeach
        </div>
        <div class="grid w-full gap-4 md:w-2/3 md:grid-cols-3">
            <div class="z-10 flex w-full flex-row rounded-xl bg-white shadow-xl hover:cursor-pointer hover:bg-gray-100">
                <div class="mx-4 my-auto flex h-12 w-12 items-center justify-center rounded-md bg-amber-500 text-white">
                    <svg class="inline h-5 w-5">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-sun') }}"></use>
                    </svg>
                </div>
                <div class="my-auto flex-1 p-4">
                    <h4 class="text-xl font-semibold">{{ __('Days') }}</h4>
                    <p class="text-gray-800">{{ $tour->days }}</p>
                </div>
            </div>
            <div class="z-10 flex w-full flex-row rounded-xl bg-white shadow-xl hover:cursor-pointer hover:bg-gray-100">
                <div class="mx-4 my-auto flex h-12 w-12 items-center justify-center rounded-md bg-black text-white">
                    <svg class="inline h-5 w-5">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-moon') }}"></use>
                    </svg>
                </div>
                <div class="my-auto flex-1 p-4">
                    <h4 class="text-xl font-semibold">{{ __('Nights') }}</h4>
                    <p class="text-gray-800">{{ $tour->nights }}</p>
                </div>
            </div>
            <div class="z-10 flex w-full flex-row rounded-xl bg-white shadow-xl hover:cursor-pointer hover:bg-gray-100">
                <div class="mx-4 my-auto flex h-12 w-12 items-center justify-center rounded-md bg-purple-500 text-white">
                    <svg class="inline h-5 w-5">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-barcode') }}"></use>
                    </svg>
                </div>
                <div class="my-auto flex-1 p-4">
                    <h4 class="text-xl font-semibold">{{ __('Tour Code') }}</h4>
                    <p class="text-gray-800">{{ $tour->tour_code }}</p>
                </div>
            </div>
        </div>

        <div>
            <h3 class="px-2 pt-4 text-3xl font-bold">{{ __('Highlight') }}</h3>
            <div class="container mx-auto px-4 pb-4" x-data="{ faqs: {{ $des->toJson() }} }">
                <div class="w-full text-lg leading-loose md:w-2/3">
                    <template x-for="faq in faqs" :key="faq.question">
                        <div class="mb-4 rounded-xl p-4 shadow-xl transition-all duration-200 hover:bg-gray-100">
                            <button class="flex w-full items-center justify-between rounded py-3 font-bold"
                                @click="faqs = faqs.map(f => ({ ...f, isOpen: f.question !== faq.question ? false : !f.isOpen}))">
                                <div class="text-xl" x-text="faq.question"></div>

                                <svg class="h-5 w-5 transform transition-transform duration-300"
                                    x-bind:class="{ 'rotate-180': faq.isOpen }" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd"
                                        d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div class="mt-2 text-gray-700 transition-opacity duration-300" x-text="faq.answer"
                                x-show="faq.isOpen" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0">
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto" id="itinerary">
        <h3 class="px-2 pt-4 text-3xl font-bold">{{ __('Itinerary') }}</h3>
        <div class="my-2 w-full text-lg leading-loose md:w-2/3">
            <a href="{{ $tour->getFirstMediaUrl('itinerary') }}" target="_blank"
                class="rounded-xl bg-green-600 px-4 py-2 text-white ring-2 ring-inset ring-offset-2 hover:bg-green-700">
                {{ __('Download Itinerary') }}
            </a>
        </div>
    </div>

    @if (app(\App\Models\Settings\GeneralSetting::class)->site_mode !== 'Enquiry')
        <livewire:front.index.tour.packages-table :tour="$tour" />
    @endif
@endsection
