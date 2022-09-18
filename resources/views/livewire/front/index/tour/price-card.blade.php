@php
    $generalSetting =app(\App\Models\Settings\GeneralSetting::class);
    $currency = $generalSetting->default_currency;
    $siteMode = $generalSetting->site_mode;
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
    <div class="flex w-full flex-col gap-1 px-4 border-b py-2">
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
    <div class="mx-auto mt-2 py-2">
        @if($siteMode == 'Online Booking')
            <a href="{{ $this->generateBookNowLink($packageId) }}"
               class="block rounded-xl bg-green-500 px-8 py-2 font-bold decoration-0">
                {{ __('Book Now!') }} &excl;
            </a>
        @endif

        @if($siteMode == 'Enquiry')
            <button
                wire:click='$emit("openModal", "front.modal.tour-enquiry", {{ json_encode(["tour" => $tour]) }})'
                class="block rounded-xl bg-green-500 px-8 py-2 font-bold">
                {{ __('Enquiry Now!') }} &excl;
            </button>
        @endif
    </div>
</div>
