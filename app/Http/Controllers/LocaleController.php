<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    public function changeLocale(Request $request, $locale): RedirectResponse
    {
        Session::put('locale', $locale);

        return back();
    }
}
