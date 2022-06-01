<div class="container mx-auto" id="packages">
    <h3 class="px-2 py-4 text-3xl font-bold">{{ __('Packages') }}</h3>
    <div class="flex flex-col gap-2 md:flex-row">
        <div class="rounded-xl border p-2">
            <h3 class="px-2 py-1 text-lg font-bold">{{ __('Month') }}</h3>
            <div class="flex flex-col gap-2 md:flex-row">
                <div class="flex w-full flex-col gap-1">
                    <label for="month" class="block px-2 text-sm opacity-70">{{ __('Month') }}</label>
                    <select id="month" class="rounded-lg" wire:model.debounce="month">
                        <option value="0">{{ __('Select Month') }}</option>
                        @foreach ($months as $key => $month)
                            <option value="{{ $key }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="rounded-xl border p-2">
            <h3 class="px-2 py-1 text-lg font-bold">{{ __('Price') }}</h3>
            <div class="flex w-full justify-between gap-2 pr-2 md:flex-row md:pr-0">
                <div class="flex w-1/2 flex-col gap-1">
                    <label for="price_from" class="px-2 text-sm opacity-70">{{ __('From') }}</label>
                    <input type="number" class="rounded-lg" id="price_from" wire:model.debounce="priceFrom"
                        placeholder="{{ __('From') }}" value="{{ now()->addMonth()->format('Y-m-d') }}">
                </div>
                <div class="flex w-1/2 flex-col gap-1">
                    <label for="price_to" class="px-2 text-sm opacity-70">{{ __('To') }}</label>
                    <input type="number" class="rounded-lg" id="price_to" wire:model.debounce="priceTo"
                        placeholder="{{ __('To') }}" value="{{ now()->addMonths(2)->format('Y-m-d') }}">
                </div>
            </div>
        </div>
        <div class="rounded-xl border p-2">
            <h3 class="px-2 py-1 text-lg font-bold">{{ __('Airlines') }}</h3>
            <div class="flex flex-col gap-2 md:flex-row">
                <div class="flex w-full flex-col gap-1">
                    <label for="airline" class="block px-2 text-sm opacity-70">{{ __('Airlines') }}</label>
                    <select name="airline" id="airline" class="rounded-lg" wire:model.debounce="airlineId">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($airlines as $airline)
                            <option value="{{ $airline['id'] }}">{{ $airline['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="relative my-2 overflow-x-auto shadow-md sm:rounded-lg" wire:loading.class="animate-pulse">
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
                @foreach ($packages as $package)
                    <tr class="border-b bg-white">
                        <th scope="row" class="whitespace-nowrap px-6 py-4 font-medium text-gray-900">
                            {{ $package->depart_time->format('d M Y H:i A') }}
                        </th>
                        <td class="px-6 py-4">{{ $package->price }}</td>
                        <td class="px-6 py-4">
                            <ul class="list-disc">
                                @foreach ($package->flight->pluck('airline.name') as $airline)
                                    <li>{{ $airline }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="px-6 py-4">{{ $package->pricings->sum('available_capacity') }}</td>
                        <td class="px-6 py-4">
                            <a href="#" class="font-medium text-blue-600 hover:underline">
                                {{ __('Book Now!') }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
