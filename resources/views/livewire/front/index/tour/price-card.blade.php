@php
    $currency = app(\App\Models\Settings\GeneralSetting::class)->default_currency;
@endphp
<div
    class="z-10 mx-auto flex w-full flex-col rounded-xl border bg-white py-2 shadow-xl md:-mt-80 md:w-max md:py-4 md:px-4">
    <div class="mx-auto pb-2">
        <h5 class="text-xl font-medium">{{ __('Price Start From') }}</h5>
        <h1 class="text-3xl font-extrabold">
            {{ money($cheapestPackagePricing->price, $currency) }}
        </h1>
    </div>
    <div class="container mx-auto border-t py-2 md:px-8">
        <div class="flex w-full flex-col gap-1">
            <label for="category" class="mx-auto">{{ __('Available Packages') }}</label>
            <select name="category" id="category" class="rounded-lg" wire:model="packageId">
                @foreach ($tour->activePackages as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->depart_time->format('Y-m-d') }}
                        {{ $category->price }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex w-full flex-col gap-1 px-4">
        @foreach ($tour->packages->find($packageId)->packagePricing as $packagePrice)
            <div class="flex w-full flex-row text-lg">
                <div class="grow md:pr-4">
                    {{ $packagePrice->name }}
                </div>
                <div>
                    {{ money($packagePrice->price, $currency) }}
                </div>
            </div>
        @endforeach
    </div>
    <div class="mx-auto mt-4 py-4">
        <a href="{{ $this->generateBookNowLink($packageId) }}"
            class="block rounded-xl bg-green-500 px-8 py-2 font-bold ring ring-lime-200 hover:animate-none hover:bg-green-400 md:animate-bounce">
            {{ __('Book Now!') }} &excl;
        </a>
    </div>
</div>
