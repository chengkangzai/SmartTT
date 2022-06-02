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
            <div class="flex flex-col gap-2 bg-white p-2 md:flex-row">
                <div class="flex w-full flex-col gap-1">
                    <label for="q" class="block px-2 text-sm opacity-70">{{ __('Keyword') }}</label>
                    <input type="text" class="rounded-lg" id="q" name="q" placeholder="{{ __('Keyword') }}">
                </div>
                <div class="flex w-full flex-col gap-1">
                    <label for="date_from" class="block px-2 text-sm opacity-70">{{ __('From') }}</label>
                    <input type="date" class="rounded-lg" id="date_from" name="dateFrom"
                           placeholder="{{ __('From') }}" min="{{ now()->format('Y-m-d') }}"
                           max="{{ $latestDepartTime }}" value="{{ now()->addMonth()->format('Y-m-d') }}">
                </div>
                <div class="flex w-full flex-col gap-1">
                    <label for="date_to" class="block px-2 text-sm opacity-70">{{ __('To') }}</label>
                    <input type="date" class="rounded-lg" id="date_to" name="dateTo"
                           placeholder="{{ __('To') }}" min="{{ now()->format('Y-m-d') }}"
                           max="{{ $latestDepartTime }}" value="{{ $latestDepartTime }}">
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
                    <label for="price_from" class="block px-2 text-sm opacity-70">{{ __('From') }}</label>
                    <input type="number" class="rounded-lg" id="price_from" name="priceFrom"
                           wire:model.debounce="priceFrom" min="0" max="{{$priceTo}}"
                           placeholder="{{ __('From') }}"/>
                </div>
                <div class="flex w-full flex-col gap-1">
                    <label for="price_to" class="block px-2 text-sm opacity-70">{{ __('To') }}</label>
                    <input type="number" class="rounded-lg" id="price_to" name="priceTo"
                           wire:model.debounce="priceTo" min="0" max="{{$priceTo}}"
                           placeholder="{{ __('To') }}"/>
                </div>
                <div class="flex flex-col">
                    <div class="flex-grow"></div>
                    <input value="{{ __('Search') }}" type="submit"
                           class="rounded bg-green-500 py-2 px-4 text-white hover:bg-green-600 hover:text-gray-50">
                </div>
            </div>
        </form>
    </div>
</div>
