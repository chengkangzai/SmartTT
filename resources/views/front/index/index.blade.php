@php
    /** @var \App\Models\Tour $tour */
@endphp

@extends('front.layouts.app')
@section('title')
    {{ __('Home') }}
@endsection
@push('style')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
@section('content')
    <div class="grid w-full place-items-center bg-cover bg-center bg-no-repeat py-20 opacity-90 md:py-40"
         style="background-image: url('{{ $imageUrl }}');">
        <div class="container bg-white/30 p-3 md:rounded">
            <button class="rounded-t bg-white px-4 py-2 font-bold text-blue-900 hover:bg-gray-100">
                <svg class="mr-2 inline h-5 w-5">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-beach-access') }}"></use>
                </svg>
                {{ __('Tours') }}
            </button>
            <form action="{{ route('front.search') }}" method="GET">
                <div class="flex flex-col gap-2 bg-white p-2 md:flex-row md:rounded">
                    <div class="w-full rounded-lg border p-2">
                        <h3 class="px-2 pb-1 text-lg font-bold">{{ __('Keyword') }}</h3>
                        <div class="flex w-full flex-col gap-1">
                            <input type="text" class="rounded-lg" id="q" name="q"
                                   aria-label="{{ __('Keyword') }}" placeholder="{{ __('Keyword') }}">
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <input value="{{ __('Search') }}" type="submit"
                               class="my-auto rounded bg-green-500 py-2 px-4 text-white hover:bg-green-600 hover:text-gray-50">
                        <small>{{ __('Powered by') }}
                            <img class="d-inline h-4" src="{{ asset('icons/algolia.png') }}" alt="Powered By Algolia"/>
                        </small>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <div class="w-full transform px-4 pt-6 text-center transition duration-300 hover:scale-110 md:w-4/12">
                    <div class="relative mb-8 flex w-full min-w-0 flex-col break-words rounded-lg bg-white shadow-lg">
                        <div class="flex-auto px-4 py-5">
                            <div
                                class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-full bg-red-400 p-3 text-center text-white shadow-lg">
                                <svg class="inline h-6 w-6">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-book') }}"></use>
                                </svg>
                            </div>
                            <h6 class="text-xl font-semibold">{{ __('Robust Planning') }}</h6>
                            <p class="mt-2 mb-4 text-gray-500">
                                {{ __('Dont know where to go ?') }}
                                <br>
                                {{ __('We always got you cover!') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="w-full transform px-4 text-center transition duration-300 hover:scale-110 md:w-4/12">
                    <div class="relative mb-8 flex w-full min-w-0 flex-col break-words rounded-lg bg-white shadow-lg">
                        <div class="flex-auto px-4 py-5">
                            <div
                                class="bg-lightBlue-400 mb-5 inline-flex h-12 w-12 items-center justify-center rounded-full p-3 text-center text-white shadow-lg">
                                <svg class="inline h-6 w-6 text-black">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-money') }}"></use>
                                </svg>
                            </div>
                            <h6 class="text-xl font-semibold">{{ __('No Hidden Cost') }}</h6>
                            <p class="mt-2 mb-4 text-gray-500">
                                {{ __('You pay exactly what we quoted !') }}
                                <br>
                                {{ __('No hidden cost!') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="w-full transform px-4 pt-6 text-center transition duration-300 hover:scale-110 md:w-4/12">
                    <div class="relative mb-8 flex w-full min-w-0 flex-col break-words rounded-lg bg-white shadow-lg">
                        <div class="flex-auto px-4 py-5">
                            <div
                                class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-400 p-3 text-center text-white shadow-lg">
                                <svg class="inline h-6 w-6">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-wallet') }}"></use>
                                </svg>
                            </div>
                            <h6 class="text-xl font-semibold">{{ __('Wallet Friendly') }}</h6>
                            <p class="mt-2 mb-4 text-gray-500">
                                {{ __('Dont you worry ! ') }} <br>
                                {{ __('We wont break your bank !') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <livewire:front.index.index.featured-tour/>
    <h2 class="my-5 text-center text-3xl font-bold">
        {{ __('Feedbacks') }}
    </h2>
    <div x-data="{ modalOpen: false, currentFeedback: {} }">

        <div class="swiper mySwiper container py-2">
            <div class="swiper-wrapper py-5">
                @foreach ($feedbacks as $feedback)
                    <div class="swiper-slide rounded-lg border bg-white p-5 shadow-md hover:cursor-pointer"
                         @click="modalOpen = true; currentFeedback = {
                             name: '{{ $feedback->name }}',
                             content: '{{ $feedback->content }}',
                             images: [
                                 @foreach ($feedback->getMedia('images') as $attachment)
                                     {
                                         url: '{{ $attachment->getUrl() }}',
                                         alt: '{{ $attachment->name }}',
                                     }, @endforeach
                             ]
                         }">
                        <div class="mb-4 flex items-start">
                            <div class="w-16 flex-none">
                                <img
                                    src="{{ 'https://ui-avatars.com/api/?name=' . urlencode($feedback->name) . '&size=64' }}"
                                    alt="{{ $feedback->name }}'s Image"
                                    class="h-16 w-16 rounded-full border-2 border-gray-300"/>
                            </div>
                            <div class="ml-4 flex-grow">
                                <p class="line font-bold">{{ $feedback->name }}</p>
                                <p class="text-gray-600">{{ $feedback->content }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <!-- Modal -->
        <div :class="{ 'hidden': !modalOpen }" x-cloak x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black bg-opacity-50 transition-all duration-300 ease-in-out">
            <div class="relative w-full max-w-lg rounded-md bg-white p-6 shadow-md"
                 @keydown.escape.window="modalOpen = false">
                <button @click="modalOpen = false"
                        class="absolute top-2 right-2 rounded-full px-4 py-2 text-xl hover:bg-gray-200">&times;
                </button>
                <h2 class="mb-4 text-xl" x-text="currentFeedback.name"></h2>
                <p x-text="currentFeedback.content"></p>

                <div x-intersect="new Swiper($refs.swiperContainer, {
                    pagination: {
                        el: $refs.swiperPagination,
                    },
                    loop: true,
                    rewind: true,
                });">
                    <div class="swiper container py-2" x-ref="swiperContainer">
                        <div class="swiper-wrapper">
                            <template x-for="(image, index) in currentFeedback.images" :key="index">
                                <div class="swiper-slide rounded-xl border bg-white shadow-md">
                                    <img :src="image.url" :alt="image.alt"
                                         class="h-64 w-full rounded-xl object-cover"/>
                                </div>
                            </template>
                        </div>
                        <div class="swiper-pagination" x-ref="swiperPagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var swiper = new Swiper(".mySwiper", {
            pagination: {
                el: ".swiper-pagination",
            },
            loop: true,
            rewind: true,
            slidesPerView: 1,
            breakpoints: {
                768: {
                    slidesPerView: 3,
                },
            },
            spaceBetween: 15,
            autoheight: true,
        });
    </script>

    <livewire:make-feedback/>
@endsection
