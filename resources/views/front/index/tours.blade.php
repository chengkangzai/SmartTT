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
            <a target="_self" href="{{ request()->fullUrl() }}#packages"
                class="my-auto font-medium hover:text-gray-500 md:text-xl">
                {{ __('Packages') }}
            </a>
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
            <div class="z-10 flex w-full flex-row rounded-xl bg-white shadow-xl">
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
            <div class="z-10 flex w-full flex-row rounded-xl bg-white shadow-xl">
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
            <div class="z-10 flex w-full flex-row rounded-xl bg-white shadow-xl">
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
            <div class="container mx-auto px-2 pb-2" x-data="{ faqs: {{ $des->toJson() }} }">
                <div class="w-full text-lg leading-loose md:w-2/3">
                    <template x-for="faq in faqs" :key="faq.question">
                        <div class="rounded-xl p-2 shadow-xl">
                            <button class="mt-4 flex w-full items-center justify-between py-3 font-bold"
                                @click="faqs = faqs.map(f => ({ ...f, isOpen: f.question !== faq.question ? false : !f.isOpen}))">
                                <div class="text-xl" x-text="faq.question"></div>
                                <span x-show="!faq.isOpen">&downarrow;</span>
                                <span x-show="faq.isOpen">&uparrow;</span>
                            </button>
                            <div class="mt-2 text-gray-700" x-text="faq.answer" x-show="faq.isOpen"></div>
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

    <livewire:front.index.tour.packages-table :tour="$tour" />
@endsection
