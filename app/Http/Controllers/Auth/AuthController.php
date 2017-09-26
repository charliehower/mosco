<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }
    public function login(Request $request)
    {

        if(Auth::attempt(['id' => $request->input('id'), 'password' => $request->input('password')],1))
            return redirect('/');
        if(Auth::attempt(['name' => $request->input('id'), 'password' => $request->input('password')],1))
            return redirect('/');
        return redirect('login')->withInput($request->except('password'))->with('message', '用户名或密码错误');
    }
    public function logout(){
        if(Auth::check()){ 
            Auth::logout();
        } 
        return redirect('login');
    }
}
