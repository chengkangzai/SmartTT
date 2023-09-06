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
                        <input type="text" class="rounded-lg" id="q" placeholder="{{ __('Keyword') }}"
                               aria-label="{{ __('Keyword') }}" wire:model.debounce="q">
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
                        <img class="d-inline h-4" src="{{ asset('icons/algolia.png') }}" alt="Powered By Algolia"/>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto my-4 px-4 md:px-0">
        <div class="flex grow flex-col gap-6 md:basis-2/3">
            @forelse ($tours as $tour)
                <a href="{{ route('front.tours', $tour) }}" class="flex w-full flex-col gap-4 rounded-lg shadow-lg transition duration-300 hover:shadow-xl md:flex-row p-4 border-2 border-gray-200 no-underline">
                    <div class="max-w-screen-sm md:max-w-xs">
                        <img srcset="{{ $tour->getFirstMedia('thumbnail')?->responsiveImages()?->getSrcset() ?? '#' }}"
                             src="{{ $tour->getFirstMediaUrl('thumbnail') }}"
                             alt="Image of {{ $tour->name }}"
                             class="aspect-video rounded-t-lg md:rounded-t-none md:rounded-l-lg"/>
                    </div>
                    <div class="container mx-auto flex flex-col justify-between">
                        <div class="pb-1 text-2xl font-bold md:py-2 md:text-3xl hover:text-indigo-500 transition duration-300">
                            {{ $tour->name }}
                        </div>
                        <div>
                            @foreach ($tour->countries as $country)
                                <span class="inline-block m-1 rounded-full bg-amber-200 py-1 px-4 text-sm font-bold">
                        {{ $country->name }}
                    </span>
                            @endforeach
                        </div>
                        <div>
                            <p>{{ __('Tour Code : ') }}<span class="font-semibold">{{ $tour->tour_code }}</span></p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="flex flex-col items-center justify-center space-y-4">
                    <img src="https://via.placeholder.com/640x480.png" alt="No tours illustration" class="w-1/2"/>
                    <h1 class="text-3xl font-bold">{{ __('No tours found') }}</h1>
                </div>
            @endforelse
                @if ($stillCanLoad)
                    <div class="container mx-auto py-4 text-center">
                        <button class="mx-auto inline-flex items-center space-x-2 px-5 py-3 rounded-full bg-indigo-500 text-white hover:bg-indigo-600 focus:outline-none focus:ring focus:ring-indigo-200 transition duration-300"
                                wire:click="loadMore">
                            <span>{{ __('Load More Tours') }}</span>
                        </button>
                    </div>
                @endif

        </div>
    </div>
</div>
