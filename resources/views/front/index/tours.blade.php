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
            <a target="_self" href="{{ request()->fullUrl() }}#packages-and-airlines"
                class="my-auto font-medium hover:text-gray-500 md:text-xl">
                {{ __('Packages & Airlines') }}
            </a>
        </div>
        <div class="z-10 mx-auto flex flex-col rounded-xl border bg-white py-4 shadow-xl md:-mt-80">
            <div class="mx-auto">
                <h5 class="text-xl font-medium">{{ __('Price Start From') }}</h5>
                <h1 class="text-3xl font-extrabold">
                    {{ $setting->default_currency_symbol }}
                    {{ number_format($cheapestPackagePricing->price, 2) }}
                </h1>
            </div>
            <div class="my-2 border"></div>
            <div class="container mx-auto py-2 px-8">
                <div class="flex w-full flex-col gap-1">
                    <label for="category" class="mx-auto">{{ __('Available Packages') }}</label>
                    <select name="category" id="category" class="rounded-lg">
                        @foreach ($tour->activePackages as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->depart_time->format('Y-m-d') }}
                                ({{ $setting->default_currency_symbol }}
                                {{ $category->price }})
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="flex w-full flex-col gap-1 px-4">
                @foreach ($tour->activePackages->first()->pricings as $packagePrice)
                    <div class="flex w-full flex-row text-lg">
                        <div class="grow">
                            {{ $packagePrice->name }}
                            ({{ $packagePrice->available_capacity }}
                            {{ __('Seat Left') }})
                        </div>
                        <div>
                            ({{ $setting->default_currency_symbol }}
                            {{ number_format($packagePrice->price, 2) }})
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mx-auto mt-4 py-4">
                <button
                    class="block animate-bounce rounded-xl bg-green-500 px-8 py-2 font-bold ring ring-lime-200 hover:animate-none hover:bg-green-400">
                    {{ __('Book Now') }} &excl;
                </button>
            </div>
        </div>
    </div>
    <div id="detail" class="container mx-auto px-4 md:px-0 md:pt-10">
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

    <div class="container" id="itinerary">
        <h3 class="px-2 pt-4 text-3xl font-bold">{{ __('Itinerary') }}</h3>
        <div class="my-2 w-full text-lg leading-loose md:w-2/3">
            <a href="{{ $tour->getFirstMediaUrl('itinerary') }}" target="_blank"
                class="rounded-xl bg-green-600 px-4 py-2 text-white ring-2 ring-inset ring-offset-2 hover:bg-green-700">
                {{ __('Download Itinerary') }}
            </a>
        </div>
    </div>

    <div class="container" id="packages-and-airlines">
        <div class="flex flex-col gap-2 md:flex-row">
            <div class="rounded-xl border p-2">
                <h3 class="px-2 py-1 text-lg font-bold">{{ __('Date') }}</h3>
                <div class="flex flex-col gap-2 md:flex-row">
                    <div class="flex w-full flex-col gap-1">
                        <label for="date_from" class="block px-2 text-sm opacity-70">{{ __('From') }}</label>
                        <input type="date" class="rounded-lg" id="date_from" placeholder="{{ __('From') }}"
                            value="{{ now()->addMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="flex w-full flex-col gap-1">
                        <label for="date_to" class="block px-2 text-sm opacity-70">{{ __('To') }}</label>
                        <input type="date" class="rounded-lg" id="date_to" placeholder="{{ __('To') }}"
                            value="{{ now()->addMonths(2)->format('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="rounded-xl border p-2">
                <h3 class="px-2 py-1 text-lg font-bold">{{ __('Price') }}</h3>
                <div class="flex flex-col gap-2 md:flex-row">
                    <div class="flex w-full flex-col gap-1">
                        <label for="price_from" class="block px-2 text-sm opacity-70">{{ __('From') }}</label>
                        <input type="number" class="rounded-lg" id="price_from" placeholder="{{ __('From') }}"
                            value="{{ now()->addMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="flex w-full flex-col gap-1">
                        <label for="price_to" class="block px-2 text-sm opacity-70">{{ __('To') }}</label>
                        <input type="number" class="rounded-lg" id="price_to" placeholder="{{ __('To') }}"
                            value="{{ now()->addMonths(2)->format('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="rounded-xl border p-2">
                <h3 class="px-2 py-1 text-lg font-bold">{{ __('Airlines') }}</h3>
                <div class="flex flex-col gap-2 md:flex-row">
                    <div class="flex w-full flex-col gap-1">
                        <label for="airline" class="block px-2 text-sm opacity-70">{{ __('Airlines') }}</label>
                        <select name="airline" id="airline" class="rounded-lg">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($airlines as $airline)
                                <option value="{{ $airline->id }}">{{ $airline->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        {{-- <table class="table my-2 border rounded-tl-lg"> --}}
        {{-- <thead> --}}
        {{-- <tr class=""> --}}
        {{-- <td>{{ __('Depart On') }}</td> --}}
        {{-- <td>{{ __('Price Range') }}</td> --}}
        {{-- <td>{{ __('Airlines') }}</td> --}}
        {{-- <td>{{ __('Seat Left') }}</td> --}}
        {{-- <td>{{ __('Action') }}</td> --}}
        {{-- </tr> --}}
        {{-- </thead> --}}
        {{-- <tbody> --}}
        {{-- @foreach ($tour->activePackages as $package) --}}
        {{-- <tr> --}}
        {{-- <td>{{ $package->depart_time }}</td> --}}
        {{-- <td>{{ $package->price }}</td> --}}
        {{-- <td> --}}
        {{-- <ul> --}}
        {{-- @foreach ($package->flight->pluck('airline.name') as $airline) --}}
        {{-- <li>{{ $airline }}</li> --}}
        {{-- @endforeach --}}
        {{-- </ul> --}}
        {{-- </td> --}}
        {{-- <td>{{ $package->seat_left }}</td> --}}
        {{-- <td> --}}
        {{-- <a href="#"> --}}
        {{-- {{__('Book Now!')}} --}}
        {{-- </a> --}}
        {{-- </td> --}}
        {{-- </tr> --}}
        {{-- @endforeach --}}
        {{-- </tbody> --}}
        {{-- </table> --}}

        <div class="relative my-2 overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3">{{ __('Depart On') }}</th>
                        <th scope="col" class="px-6 py-3">{{ __('Price Range') }}</th>
                        <th scope="col" class="px-6 py-3">{{ __('Airlines') }}</th>
                        <th scope="col" class="px-6 py-3">{{ __('Seat Left') }}</th>
                        <th scope="col" class="px-6 py-3">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($tour->activePackages as $package)
                    <tr class="border-b bg-white">
                        <th scope="row" class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">
                            {{$package->depart_time->format('d M Y H:i')}}
                        </th>
                        <td class="px-6 py-4">{{$package->price}}</td>
                        <td class="px-6 py-4">
                            <ul class="list-disc">
                                @foreach($package->flight->pluck('airline.name') as $airline)
                                    <li>{{$airline}}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4">{{$package->pricings->sum('available_capacity')}}</td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">
                                {{__('Book Now!')}}
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
