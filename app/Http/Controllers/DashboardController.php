<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


class DashboardController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        return view('smartTT.dashboard');
    }
}
