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