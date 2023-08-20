<div class="bg-gray-100 px-4 pb-2">
    <div class="container mx-auto py-10">
        <h2
            class="inline border-b-2 border-b-violet-500 bg-gradient-to-r from-violet-500 to-cyan-500 bg-clip-border bg-clip-border bg-clip-text text-3xl font-extrabold text-transparent underline underline-offset-8">
            {{ __('Featured Tour') }}
        </h2>
    </div>
    <div class="container mx-auto my-4 grid items-stretch gap-4 md:grid-cols-3">
        @forelse  ($tours as $tour)
            <div
                class="flex flex-col overflow-hidden rounded-lg bg-white shadow-lg transition duration-300 hover:scale-105">
                <div class="aspect-video w-full">
                    <a href="{{ route('front.tours', $tour) }}">
                        <img srcset="{{ $tour->getFirstMedia('thumbnail')?->responsiveImages()?->getSrcset() }}"
                            src="{{ $tour->getFirstMediaUrl('thumbnail') }}"
                            onload="window.requestAnimationFrame(function(){if(!(size=getBoundingClientRect().width))return;onload=null;sizes=Math.ceil(size/window.innerWidth*100)+'vw';});"
                            alt="Image of {{ $tour->name }}" class="aspect-video w-full" />
                    </a>
                </div>
                <div class="p-4 text-center md:px-7 md:pb-0">
                    <h3 class="text-dark block text-xl font-semibold hover:text-black md:mb-4">
                        {{ $tour->name }}
                    </h3>
                </div>
                <div class="grow">

                </div>
                <a href="{{ route('front.tours', $tour) }}"
                    class="mx-auto mb-6 w-fit rounded-full border py-2 px-7 text-base font-medium transition hover:border-black hover:bg-white hover:text-black">
                    {{ __('View Details') }}
                </a>
            </div>
        @empty
            <div class="container mx-auto py-10">
                <h2 class="text-3xl font-extrabold text-center text-gray-600">
                    {{ __('No tours found') }}
                </h2>
            </div>
        @endforelse
    </div>
    @if ($stillCanLoad)
        <div class="container mx-auto py-2">
            <button class="mx-auto block animate-bounce rounded px-4 py-2 text-center hover:bg-gray-200"
                wire:click="loadMore">
                {{ __('More') }} &downarrow;
            </button>
        </div>
    @endif
</div>
