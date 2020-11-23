<?php

namespace Modules\Auth\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Modules\Auth\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Cache;
use \Modules\Accounting\Models\Year;
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
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:office')->except('logout');
    }
    
    /**
    * Show the application's login form.
    *
    * @return \Illuminate\Http\Response
    */
    public function showLoginForm()
    {
        return view('auth::auth.login');
    }
    
    /**
    * Get the login username to be used by the controller.
    *
    * @return string
    */
    public function username()
    {
        return 'username';
    }
    
    /**
    * Attempt to log the user into the application.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return bool
    */
    protected function attemptLogin(Request $request)
    {
        if (auth()->guard('office')->attempt($this->credentials($request), $request->filled('remember'))) {
            return true;
        } else {
            return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
            );
        }
    }
    
    /**
    * Send the response after the user was authenticated.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $request->session()->put('year', \Modules\Accounting\Models\Year::active());
        $this->clearLoginAttempts($request);
        
        if (auth()->guard('office')->check()) {
            return redirect()->to('/office');
        }
        
        return $this->authenticated($request, $this->guard()->user())
        ?: redirect()->intended($this->redirectPath());
    }
    
    /**
    * The user has logged out of the application.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return mixed
    */
    protected function loggedOut(Request $request)
    {
        Cache::flush();
        
        return redirect('/login');
    }
}