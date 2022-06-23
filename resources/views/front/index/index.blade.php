@php
/** @var \App\Models\Tour $tour */
@endphp

@extends('front.layouts.app')
@section('title')
    {{ __('Home') }}
@endsection

@section('content')
    <livewire:front.index.tour.search-tour-card />
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
    <livewire:front.index.index.featured-tour />
@endsection
