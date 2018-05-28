<?php

namespace Corp\Http\Controllers\Auth;

use Corp\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    public function username()
    {
        return 'login';
    }

    public function showLoginForm()
    {
        return view(env('THEME') . './admin/login')->with('title', 'Вход на сайт');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $loginView;
    protected $redirectTo = 'admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

    }

}
