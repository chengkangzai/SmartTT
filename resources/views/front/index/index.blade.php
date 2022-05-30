@php
/** @var \App\Models\Tour $tour */
@endphp

@extends('front.layouts.app')
@section('title')
    {{ __('Home') }}
@endsection

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
            <div class="flex flex-col gap-2 bg-white p-2 md:flex-row">
                <div class="flex w-full flex-col gap-1">
                    <label for="keyword" class="block px-2 text-sm opacity-70">{{ __('Keyword') }}</label>
                    <input type="text" class="rounded-lg" id="keyword" placeholder="{{ __('Keyword') }}">
                </div>
                <div class="flex w-full flex-col gap-1">
                    <label for="from" class="block px-2 text-sm opacity-70">{{ __('From') }}</label>
                    <input type="date" class="rounded-lg" id="from" placeholder="{{ __('From') }}"
                        value="{{ now()->addMonth()->format('Y-m-d') }}">
                </div>
                <div class="flex w-full flex-col gap-1">
                    <label for="to" class="block px-2 text-sm opacity-70">{{ __('To') }}</label>
                    <input type="date" class="rounded-lg" id="to" placeholder="{{ __('To') }}"
                        value="{{ now()->addMonths(2)->format('Y-m-d') }}">
                </div>
            </div>
            <div class="flex flex-col gap-2 rounded-b bg-white p-2 md:flex-row">
                <div class="flex w-full flex-col gap-1">
                    <label for="category" class="block px-2 text-sm opacity-70">{{ __('Category') }}</label>
                    <select name="category" id="category" class="rounded-lg">
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex w-full flex-col gap-1">
                    <label for="countries" class="block px-2 text-sm opacity-70">{{ __('Countries') }}</label>
                    <select name="countries" id="countries" class="rounded-lg">
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <div class="flex-grow"></div>
                    <button class="rounded bg-green-500 py-2 px-4 text-white hover:bg-green-600 hover:text-gray-50">
                        {{ __('Search') }}
                    </button>
                </div>
            </div>
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
                                {{ __('Don\'t know where to go ?') }}
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
                                {{ __('Don\'t you worry ! ') }} <br>
                                {{ __('We won\'t break your bank !') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="bg-gray-100 px-4">
        <div class="container mx-auto py-10">
            <h2
                class="inline border-b-2 border-b-violet-500 bg-gradient-to-r from-violet-500 to-cyan-500 bg-clip-border bg-clip-border bg-clip-text text-3xl font-extrabold text-transparent underline underline-offset-8">
                {{ __('Featured Tour') }}
            </h2>
        </div>
        <div class="container mx-auto grid items-stretch gap-4 md:grid-cols-3">
            @foreach ($tours as $tour)
                <div
                    class="flex flex-col overflow-hidden rounded-lg bg-white shadow-lg transition duration-300 hover:scale-105">
                    <img src="{{ $tour->getFirstMediaUrl('thumbnail') }}" alt="image" class="aspect-video w-full" />
                    <div class="p-4 text-center md:px-7 md:pb-0">
                        <h3>
                            <a href="javascript:void(0)"
                                class="text-dark block text-xl font-semibold hover:text-black md:mb-4">
                                {{ $tour->name }}
                            </a>
                        </h3>
                    </div>
                    <div class="flex-grow">

                    </div>
                    <a href="javascript:void(0)"
                        class="mx-auto mb-6 w-fit rounded-full border py-2 px-7 text-base font-medium transition hover:border-black hover:bg-white hover:text-black">
                        {{ __('View Details') }}
                    </a>
                </div>
            @endforeach
        </div>
        <div class="container mx-auto my-4 py-2">
            <button class="mx-auto block animate-bounce rounded px-4 py-2 text-center hover:bg-gray-200">
                {{ __('More') }} &downarrow;
            </button>
        </div>
    </div>
@endsection
