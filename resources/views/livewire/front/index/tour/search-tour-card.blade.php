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
                        <label for="q" class="hidden px-2 text-sm opacity-70 md:block">{{ __('Keyword') }}</label>
                        <input type="text" class="rounded-lg" id="q" name="q" placeholder="{{ __('Keyword') }}">
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
                                <input type="date" class="w-full rounded-lg" id="date_from" name="dateFrom"
                                       min="{{ now()->format('Y-m-d') }}"
                                       max="{{ $latestDepartTime }}"
                                       value="{{ now()->addMonth()->format('Y-m-d') }}" />
                            </div>
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="date_to" class="block px-2 text-sm opacity-70">{{ __('Date To') }}</label>
                                <input type="date" class="w-full rounded-lg" id="date_to" name="dateTo"
                                       min="{{ now()->format('Y-m-d') }}" max="{{ $latestDepartTime }}"
                                       value="{{ now()->addMonths(2)->format('Y-m-d') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full rounded-lg border px-2 py-2">
                    <h3 class="px-2 text-lg font-bold">{{ __('Price') }}</h3>
                    <div class="flex flex-col md:w-full">
                        <div class="flex flex-row justify-between gap-2 md:w-full">
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="price_from"
                                       class="px-2 text-sm opacity-70">{{ __('Price From') }}</label>
                                <input type="number" class="w-full rounded-lg" id="price_from" name="priceFrom"
                                       wire:model.debounce="priceFrom" placeholder="{{ __('Price From') }}" />
                            </div>
                            <div class="w-full md:flex md:flex-col md:gap-1">
                                <label for="price_to"
                                       class="block px-2 text-sm opacity-70">{{ __('Price To') }}</label>
                                <input type="number" class="w-full rounded-lg" id="price_to" name="priceTo"
                                       wire:model.debounce="priceTo" placeholder="{{ __('Price To') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col">
                    <input value="{{ __('Search') }}" type="submit"
                           class="my-auto rounded bg-green-500 py-2 px-4 text-white hover:bg-green-600 hover:text-gray-50">
                    <small>{{ __('Powered by') }}
                        <img class="d-inline h-4" src="{{ asset('icons/algolia.png') }}"
                             alt="Powered By Algolia" />
                    </small>
                </div>
            </div>
        </form>
    </div>
</div>
