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
    <title>{{ $site_name }}</title>
    <meta name="theme-color" content="#ffffff">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

    <header class="header position-sticky top-0">
        <a class="header-brand" href="{{ route('front.index') }}">
            <img src="{{ asset('landscape_logo.png') }}" height="24" class="d-inline-block align-top"
                alt="Brand Logo">
        </a>
        <ul class="header-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('front.index') }}">{{ __('Home') }}
                    <span class="visually-hidden">(current)</span>
                </a>
            </li>
        </ul>
    </header>
    <div class="bg-light min-vh-100 d-flex flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('js/coreui.bundle.min.js') }}"></script>
</body>

</html>
