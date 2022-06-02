<div class="z-10 mx-auto flex flex-col rounded-xl border bg-white py-4 px-4 shadow-xl md:-mt-80">
    <div class="mx-auto">
        <h5 class="text-xl font-medium">{{ __('Price Start From') }}</h5>
        <h1 class="text-3xl font-extrabold">
            {{ $default_currency_symbol }}
            {{ number_format($cheapestPackagePricing->price, 2) }}
        </h1>
    </div>
    <div class="container mx-auto border-t py-2 px-8">
        <div class="flex w-full flex-col gap-1">
            <label for="category" class="mx-auto">{{ __('Available Packages') }}</label>
            <select name="category" id="category" class="rounded-lg" wire:model="packageId">
                @foreach ($tour->activePackages as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->depart_time->format('Y-m-d') }}
                        ({{ $default_currency_symbol }}
                        {{ $category->price }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex w-full flex-col gap-1">
        @foreach ($tour->packages->find($packageId)->pricings as $packagePrice)
            <div class="flex w-full flex-row text-lg">
                <div class="grow">
                    {{ $packagePrice->name }}
                    ({{ $packagePrice->available_capacity }}
                    {{ __('Seat Left') }})
                </div>
                <div>
                    ({{ $default_currency_symbol }}
                    {{ number_format($packagePrice->price, 2) }})
                </div>
            </div>
        @endforeach
    </div>
    <div class="mx-auto mt-4 py-4">
        <a href="{{route('bookings.create',['package'=>$packageId])}}"
           class="block animate-bounce rounded-xl bg-green-500 px-8 py-2 font-bold ring ring-lime-200 hover:animate-none hover:bg-green-400">
            {{ __('Book Now') }} &excl;
        </a>
    </div>
</div>
