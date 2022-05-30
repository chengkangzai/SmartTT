<div class="bg-gray-100 px-4 pb-2">
    <div class="container mx-auto py-10">
        <h2
            class="inline border-b-2 border-b-violet-500 bg-gradient-to-r from-violet-500 to-cyan-500 bg-clip-border bg-clip-border bg-clip-text text-3xl font-extrabold text-transparent underline underline-offset-8">
            {{ __('Featured Tour') }}
        </h2>
    </div>
    <div class="container mx-auto grid items-stretch gap-4 md:grid-cols-3 my-4">
        @foreach ($tours as $tour)
            <div
                class="flex flex-col overflow-hidden rounded-lg bg-white shadow-lg transition duration-300 hover:scale-105">
                <a href="{{ route('front.tours', $tour) }}">
                    <img src="{{ $tour->getFirstMediaUrl('thumbnail') }}" alt="image" class="aspect-video w-full"/>
                </a>
                <div class="p-4 text-center md:px-7 md:pb-0">
                    <h3 class="text-dark block text-xl font-semibold hover:text-black md:mb-4">
                        {{ $tour->name }}
                    </h3>
                </div>
                <div class="flex-grow">

                </div>
                <a href="{{ route('front.tours', $tour) }}"
                   class="mx-auto mb-6 w-fit rounded-full border py-2 px-7 text-base font-medium transition hover:border-black hover:bg-white hover:text-black">
                    {{ __('View Details') }}
                </a>
            </div>
        @endforeach
    </div>
    @if($stillCanLoad)
        <div class="container mx-auto py-2">
            <button class="mx-auto block animate-bounce rounded px-4 py-2 text-center hover:bg-gray-200"
                    wire:click="loadMore">
                {{ __('More') }} &downarrow;
            </button>
        </div>
    @endif
</div>