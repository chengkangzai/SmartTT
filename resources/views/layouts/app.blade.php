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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel='icon' href='/icon.gif' type='image/gif' sizes='16x16'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @livewireStyles
</head>

<body>
    <div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
        <div class="sidebar-brand d-none d-md-flex">
            <a href="{{ route('home') }}" class="sidebar-brand-full">
                <img src="{{ asset('button_smart-tt.png') }}" alt="logo" width="118">
            </a>

            <a href="{{ route('home') }}" class="sidebar-brand-narrow">
                <img src="{{ asset('button_smart-tt.png') }}" alt="logo" width="46">
            </a>
        </div>
        @include('layouts.navigation')
        <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
        <header class="header header-sticky mb-4">
            <div class="container-fluid">
                <button class="header-toggler px-md-0 me-md-3" type="button"
                    onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
                    <svg class="icon icon-lg">
                        <use xlink:href="{{ asset('icons/coreui.svg#cil-menu') }}"></use>
                    </svg>
                </button>
                <a class="header-brand d-md-none" href="#">
                    <img src="{{ asset('button_smart-tt.png') }}" alt="logo" width="118">
                </a>
                <ul class="header-nav d-none d-md-flex">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('Home') }}</a>
                    </li>
                </ul>
                <ul class="header-nav ms-auto">

                </ul>
                <ul class="header-nav ms-3">
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            {{ __('Language') }}
                            <svg class="icon">
                                <use xlink:href="{{ asset('icons/coreui.svg#cil-globe-alt') }}"></use>
                            </svg>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            @foreach ($setting->supported_language as $lang)
                                <a class="dropdown-item {{ App::currentLocale() == $lang ? 'active' : '' }} "
                                    href="{{ route('setLocale', $lang) }}">
                                    {{ $lang }}
                                </a>
                            @endforeach
                        </div>
                    </li>
                    |
                    <li class="nav-item dropdown">
                        <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button"
                            aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end pt-0">
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <svg class="icon me-2">
                                    <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                                </svg>
                                {{ __('My profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{ asset('icons/coreui.svg#cil-account-logout') }}"></use>
                                    </svg>
                                    {{ __('Logout') }}
                                </a>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </header>
        <div class="body flex-grow-1 px-3">
            <div class="container-lg">
                @yield('content')
            </div>
        </div>
        {{-- <footer class="footer"> --}}
        {{-- <div> --}}
        {{-- <a href="https://coreui.io">CoreUI </a><a href="https://coreui.io">Bootstrap Admin Template</a> &copy; 2021 --}}
        {{-- creativeLabs. --}}
        {{-- </div> --}}
        {{-- <div class="ms-auto">Powered by&nbsp;<a href="https://coreui.io/bootstrap/ui-components/">CoreUI UI --}}
        {{-- Components</a></div> --}}
        {{-- </footer> --}}
    </div>
    @yield('modal')
    @include('partials.success-toast')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.5/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        [].slice
            .call(document.querySelectorAll('.toast'))
            .map((toastEl) => new coreui.Toast(toastEl))
            .map((toast) => toast.show());

        [].slice
            .call(document.querySelectorAll('[data-coreui-toggle="tooltip"]'))
            .map((tooltipTriggerEl) => new coreui.Tooltip(tooltipTriggerEl))
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.fn.select2.defaults.set("selectionCssClass", "py-2");
        $.fn.select2.defaults.set("placeholder", "{{ __('Please Select') }}");

        $.extend(true, $.fn.dataTable.defaults, {
            bInfo: false,
            paging: false,
            order: [
                [0, "desc"]
            ],
        });
    </script>
    @livewireScripts
    @stack('script')
</body>

</html>
