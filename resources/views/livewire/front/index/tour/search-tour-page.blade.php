@php
    /** @var \App\Models\Tour $tour */
@endphp
@section('title')
    {{ __('Search Tour') }}
@endsection

<div>
    <div
        class="grid w-full place-items-center border-t-2 border-gray-700 bg-gray-800 bg-cover bg-center bg-no-repeat py-8 px-4">
        <div class="container bg-white/30 p-2 md:rounded">
            <div class="flex flex-col gap-2 bg-white p-2 md:flex-row md:rounded">
                <div class="w-full rounded-lg border p-2">
                    <h3 class="px-2 pb-1 text-lg font-bold">{{ __('Keyword') }}</h3>
                    <div class="flex w-full flex-col gap-1">
                        <label for="q" class="hidden px-2 text-sm opacity-70 md:block">{{ __('Keyword') }}</label>
                        <input type="text" class="rounded-lg" id="q" placeholder="{{ __('Keyword') }}"
                            wire:model.debounce="q">
                    </div>
                </div>
                <div class="w-full rounded-lg border p-2">
                    <h3 class="px-2 text-lg font-bold">{{ __('Date') }}</h3>
                    <div class="flex flex-col md:w-full">
                        <div class="flex flex-row justify-between gap-2 md:w-full">
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="date_from" class="block px-2 text-sm opacity-70">
                                    {{ __('Date From') }}
                                </label>
                                <input type="date" class="w-full rounded-lg" id="date_from"
                                    wire:model.debounce="dateFrom" min="{{ now()->format('Y-m-d') }}"
                                    max="{{ $latestDepartTime }}" value="{{ now()->addMonth()->format('Y-m-d') }}" />
                            </div>
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="date_to" class="block px-2 text-sm opacity-70">{{ __('Date To') }}</label>
                                <input type="date" class="w-full rounded-lg" id="date_to"
                                    wire:model.debounce="dateTo" min="{{ now()->format('Y-m-d') }}"
                                    max="{{ $latestDepartTime }}"
                                    value="{{ now()->addMonths(2)->format('Y-m-d') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg border px-2 py-2 md:hidden">
                    <h3 class="px-2 text-lg font-bold">{{ __('Price') }}</h3>
                    <div class="flex flex-col md:w-full">
                        <div class="flex flex-row justify-between gap-2 md:w-full">
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="price_from" class="px-2 text-sm opacity-70">{{ __('Price From') }}</label>
                                <input type="number" class="w-full rounded-lg" id="price_from" step="50"
                                    wire:model.debounce="priceFrom" placeholder="{{ __('Price From') }}" />
                            </div>
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="price_to"
                                    class="block px-2 text-sm opacity-70">{{ __('Price To') }}</label>
                                <input type="number" class="w-full rounded-lg" id="price_to" step="50"
                                    wire:model.debounce="priceTo" placeholder="{{ __('Price To') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg border p-2 md:hidden">
                    <h3 class="px-2 pb-1 text-lg font-bold">{{ __('Category') }}</h3>
                    <div class="flex w-full flex-col gap-1">
                        <label for="category" class="px-2 text-sm opacity-70">{{ __('Category') }}</label>
                        <select id="category" class="rounded-lg" wire:model.debounce="category">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex flex-col">
                    <input value="{{ __('Search') }}" type="submit"
                        class="my-auto rounded bg-green-500 py-2 px-4 text-white hover:bg-green-600 hover:text-gray-50">
                    <small>{{ __('Powered by') }}
                        <img class="d-inline h-4" src="{{ asset('icons/algolia.png') }}" alt="Powered By Algolia" />
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto my-4 px-4 md:px-0">
        <div class="gap-6 md:flex md:flex-row">
            <div class="hidden h-full gap-1 rounded-lg p-3 shadow-md md:flex md:flex-col">
                <div class="hidden rounded-lg border bg-white px-2 py-2 md:block">
                    <h3 class="px-2 text-lg font-bold">{{ __('Price') }}</h3>
                    <div class="flex flex-col md:w-full">
                        <div class="flex flex-row justify-between gap-2 md:w-full">
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="price_from" class="px-2 text-sm opacity-70">
                                    {{ __('Price From') }}
                                </label>
                                <input type="number" class="w-full rounded-lg" id="price_from" step="50"
                                    wire:model.debounce="priceFrom" min="0"
                                    placeholder="{{ __('Price From') }}" />
                            </div>
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="price_to" class="block px-2 text-sm opacity-70">
                                    {{ __('Price To') }}
                                </label>
                                <input type="number" class="w-full rounded-lg" id="price_to" step="50"
                                    wire:model.debounce="priceTo" min="0"
                                    placeholder="{{ __('Price To') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hidden rounded-lg border bg-white p-2 md:block">
                    <h3 class="px-2 pb-1 text-lg font-bold">{{ __('Category') }}</h3>
                    <div class="flex w-full flex-col gap-1">
                        <label for="category" class="px-2 text-sm opacity-70">{{ __('Category') }}</label>
                        <select id="category" class="rounded-lg" wire:model.debounce="category">
                            <option value="">{{ __('All') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex grow flex-col gap-3" wire:loading.class="animate-pulse opacity-70">
                @forelse ($tours as $tour)
                    <div
                        class="flex w-full flex-col gap-2 rounded-lg shadow-md transition duration-300 hover:scale-105 md:flex-row">
                        <a href="{{ route('front.tours', $tour) }}" class="max-w-screen-sm md:max-w-xs">
                            <img srcset="{{ $tour->getFirstMedia('thumbnail')?->responsiveImages()?->getSrcset() ?? '#' }}"
                                src="{{ $tour->getFirstMediaUrl('thumbnail') }}"
                                onload="window.requestAnimationFrame(function(){if(!(size=getBoundingClientRect().width))return;onload=null;sizes=Math.ceil(size/window.innerWidth*100)+'vw';});"
                                alt="Image of {{ $tour->name }}"
                                class="aspect-video rounded-t-lg md:rounded-t-none md:rounded-l-lg" />
                        </a>
                        <div class="container mx-auto flex flex-col py-2">
                            <a href="{{ route('front.tours', $tour) }}"
                                class="pb-1 text-2xl font-bold md:py-2 md:text-3xl">
                                {{ $tour->name }}
                            </a>
                            <div>
                                @foreach ($tour->countries as $country)
                                    <span class="inline-block rounded-full bg-amber-200 py-1 px-4 text-sm font-bold">
                                        {{ $country->name }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="grow"></div>
                            <div>
                                <p>{{ __('Tour Code : ') }}{{ $tour->tour_code }}</p>
                            </div>
                            <div class="flex flex-row-reverse content-center text-xl font-medium">
                                <span class="my-auto text-xl font-extrabold md:text-3xl">
                                    {{ $default_currency_symbol }}
                                    {{ $this->getCheapestPrice($tour) }}
                                </span>
                                <p class="my-auto px-2">{{ __('Price start from') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center">
                        <h1 class="text-3xl font-bold">{{ __('No tours found') }}</h1>
                    </div>
                @endforelse
                @if ($stillCanLoad)
                    <div class="container mx-auto py-2">
                        <button class="mx-auto block animate-bounce rounded px-4 py-2 text-center hover:bg-gray-200"
                            wire:click="loadMore">
                            {{ __('More') }} &downarrow;
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
