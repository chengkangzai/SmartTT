<?php

namespace App\Http\Middleware;

use App\Models\Settings\GeneralSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Locale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = Session::get('locale', app(GeneralSetting::class)->default_language);

        App::setLocale($locale);

        return $next($request);
    }
}
