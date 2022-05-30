@php
$setting = app(App\Models\Settings\GeneralSetting::class);
$site_name = $setting->site_name;
$language = $setting->default_language;
@endphp
<!DOCTYPE html>
<html lang="{{ $language }}">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>@yield('title') - {{ $site_name }}</title>
    <meta name="theme-color" content="#ffffff">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('tailwind/app.css') }}" rel="stylesheet">
    <link rel='icon' href='/icon.gif' type='image/gif' sizes='16x16'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
    @stack('style')
</head>

<body>
    @yield('modal')
    <div class="flex min-h-screen flex-col overscroll-none">
        <header class="top-0 z-50 w-full">
            <div class="bg-gray-200 dark:bg-gray-700 dark:text-gray-200">
                <div
                    class="container mx-auto flex hidden flex-col px-4 md:flex md:flex-row md:items-center md:justify-between">
                    <p class="inline text-xs">
                        <svg class="inline h-5 w-5">
                            <use xlink:href="{{ asset('icons/coreui.svg#cil-phone') }}"></use>
                        </svg>
                        {{ $setting->company_phone }}
                    </p>
                    <nav class="hidden flex-grow flex-col gap-2 md:flex md:flex-row md:justify-end md:pb-0">
                        <div @click.away="open = false" class="relative inline" x-data="{ open: false }">
                            <button @click="open = !open" aria-label="Drop Down trigger"
                                class="focus:shadow-outline cursor-pointer rounded bg-transparent py-1 px-2 hover:bg-gray-800 hover:text-gray-100 focus:bg-gray-800 focus:text-gray-100 focus:outline-none dark:bg-transparent dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:bg-gray-600 dark:focus:text-white">
                                <span>
                                    {{ __('Language') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <svg viewBox="0 0 20 20" :class="{'rotate-180': open, 'rotate-0': !open}"
                                        class="inline h-4 w-4 transform fill-current transition-transform duration-200">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z">
                                        </path>
                                    </svg>
                                </span>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-1 w-full origin-top-right rounded-md shadow-lg md:w-48">
                                <div class="rounded-md bg-white px-2 py-2 shadow dark:bg-gray-800">
                                    <a class="@if (app()->getLocale() == 'en') bg-gray-200 dark:bg-gray-600 @endif focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-sm hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none dark:bg-transparent dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:bg-gray-600 dark:focus:text-white md:mt-0"
                                        href="{{ route('setLocale', 'en') }}">English</a>
                                    <a class="@if (app()->getLocale() == 'zh') bg-gray-200 dark:bg-gray-600 @endif focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-sm hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none dark:bg-transparent dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:bg-gray-600 dark:focus:text-white md:mt-0"
                                        href="{{ route('setLocale', 'zh') }}">简体中文</a>
                                    <a class="@if (app()->getLocale() == 'ms') bg-gray-200 dark:bg-gray-600 @endif focus:shadow-outline mt-2 block rounded-lg bg-transparent px-4 py-2 text-sm hover:bg-gray-200 hover:text-gray-900 focus:bg-gray-200 focus:text-gray-900 focus:outline-none dark:bg-transparent dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:bg-gray-600 dark:focus:text-white md:mt-0"
                                        href="{{ route('setLocale', 'ms') }}">Bahasa Malaysia</a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="bg-gray-200 dark:bg-gray-800 dark:text-gray-200">
                <div x-data="{ open: false }"
                    class="container mx-auto flex flex-col px-4 py-2 md:flex-row md:items-center md:justify-between">
                    <div class="flex flex-row items-center justify-between">
                        <a href="{{ route('front.index') }}"
                            class="focus:shadow-outline rounded-lg text-lg font-bold uppercase tracking-widest hover:text-gray-300 focus:outline-none">
                            {{ $setting->site_name }}
                        </a>

                        <button class="focus:shadow-outline rounded-lg focus:outline-none md:hidden"
                            @click="open = !open">
                            <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 w-6">
                                <path x-show="!open" fill-rule="evenodd" clip-rule="evenodd"
                                    class="text-dark fill-current dark:hover:text-white"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z">
                                </path>
                                <path x-show="open" fill-rule="evenodd" clip-rule="evenodd"
                                    class="text-dark fill-current dark:hover:text-white"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <nav :class="{'flex': open, 'hidden': !open}"
                        class="hidden flex-grow flex-col gap-2 py-2 md:flex md:flex-row md:justify-end md:py-0">
                        @auth
                            <a href="{{ route('home') }}"
                                class="m-1 my-auto rounded border p-2 font-medium leading-none hover:bg-gray-800 hover:text-gray-100 focus:bg-gray-800 focus:text-gray-100 focus:outline-none dark:bg-transparent dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:bg-gray-600 dark:focus:text-white">
                                {{ __('Dashboard') }}</a>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                class="m-1 my-auto rounded border p-2 font-medium leading-none hover:bg-gray-800 hover:text-gray-100 focus:bg-gray-800 focus:text-gray-100 focus:outline-none dark:bg-transparent dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:bg-gray-600 dark:focus:text-white">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}"
                                class="m-1 my-auto rounded border p-2 font-medium leading-none hover:bg-gray-800 hover:text-gray-100 focus:bg-gray-800 focus:text-gray-100 focus:outline-none dark:bg-transparent dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:bg-gray-600 dark:focus:text-white">
                                {{ __('Login') }}
                            </a>
                            <a href="{{ route('register') }}"
                                class="m-1 my-auto rounded border p-2 font-medium leading-none hover:bg-gray-800 hover:text-gray-100 focus:bg-gray-800 focus:text-gray-100 focus:outline-none dark:bg-transparent dark:text-gray-200 dark:hover:bg-gray-600 dark:hover:text-white dark:focus:bg-gray-600 dark:focus:text-white">
                                {{ __('Register') }}
                            </a>
                        @endguest
                    </nav>
                </div>
            </div>
        </header>

        <div class="flex flex-1 md:flex-row">
            <main class="w-full bg-white md:px-0">
                @yield('content')
            </main>
        </div>

        @include('front.partial.footer')
    </div>
    <script src="{{ asset('js/alpine.js') }}"></script>
    @livewireScripts
    @stack('script')
</body>

</html>
