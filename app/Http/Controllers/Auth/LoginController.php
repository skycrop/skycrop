<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:farmer')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login', [
            'breadcrumbs' => [
                'title' => __('Login'),
            ],
        ]);
    }

    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Determine if login is email or mobile
        $loginInput = $request->input('login');
        $loginType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone_number';        

        // Attempt login
        if (Auth::guard('farmer')->attempt([
                $loginType => $loginInput,
                'password'  => $request->password,
            ],
            $request->filled('remember')
        )) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Failed login
        return back()->withErrors([
            'login' => __('The provided credentials do not match our records.'),
        ])->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

}
