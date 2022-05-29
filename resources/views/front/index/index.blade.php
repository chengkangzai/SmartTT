@extends('front.layouts.app')
@section('title')
    {{ __('Home') }}
@endsection

@push('style')
    <style>
        #heroCover {
            background-image: url("{{ $imageUrl }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
@endpush

@section('content')
    <div
        class=" grid w-full place-items-center opacity-90 md:py-40" id="heroCover">
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
                    <input type="date" class="rounded-lg" id="from" placeholder="{{ __('From') }}">
                </div>
                <div class="flex w-full flex-col gap-1">
                    <label for="to" class="block px-2 text-sm opacity-70">{{ __('To') }}</label>
                    <input type="date" class="rounded-lg" id="to" placeholder="{{ __('To') }}">
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
    <section class="py-10 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap">
                <div class="lg:pt-12 pt-6 w-full md:w-4/12 px-4 text-center">
                    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                        <div class="px-4 py-5 flex-auto">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-red-400">
                                <svg class="inline h-6 w-6">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-book') }}"></use>
                                </svg>
                            </div>
                            <h6 class="text-xl font-semibold">Robust Planning</h6>
                            <p class="mt-2 mb-4 text-gray-500">
                                Don't know where to go ?
                                <br>
                                We always got you cover!
                            </p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-4/12 px-4 text-center">
                    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                        <div class="px-4 py-5 flex-auto">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-lightBlue-400">
                                <svg class="inline h-6 w-6 text-black" >
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-money') }}"></use>
                                </svg>
                            </div>
                            <h6 class="text-xl font-semibold">No Hidden Cost</h6>
                            <p class="mt-2 mb-4 text-gray-500">
                                You pay exactly what
                                id quoted !
                            </p>
                        </div>
                    </div>
                </div>
                <div class="pt-6 w-full md:w-4/12 px-4 text-center">
                    <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-8 shadow-lg rounded-lg">
                        <div class="px-4 py-5 flex-auto">
                            <div
                                class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 mb-5 shadow-lg rounded-full bg-emerald-400">
                                <svg class="inline h-6 w-6" >
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-wallet') }}"></use>
                                </svg>
                            </div>
                            <h6 class="text-xl font-semibold">Wallet Friendly</h6>
                            <p class="mt-2 mb-4 text-gray-500">
                                Don't you worry ! <br>
                                We won't break your bank !
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
