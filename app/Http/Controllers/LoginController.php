<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Response;
use Session;
use Validator;
use function dd;
use function view;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::viaRemember()) {
            $this->dashboard();
        }
        return view('smartTT.login');
    }

    public function registration()
    {
        return view('smartTT.register');
    }

    public function postLogin(Request $request)
    {
        request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, $request->get('remember'))) {
            // Authentication passed...
            //TODO
            //1. Check "remember me"
            //2. Redirect to admin page
            //3. Middleware protection of admin page
            return redirect()->intended('dashboard');
        }
        return  view('smartTT.login');
//        return view::("smartTT.login")->withSuccess('Oppes! You have entered invalid credentials');
    }

    public function postRegistration(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();

        $check = $this->create($data);

        return view("smartTT.dashboard")->with('Great! You have Successfully loggedin');
    }

    public function dashboard()
    {
        if (Auth::check()) {
            return view('smartTT.dashboard');
        }
        return view("smartTT.login")->with('Opps! You do not have access');
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return view('smartTT.login');
    }
}
