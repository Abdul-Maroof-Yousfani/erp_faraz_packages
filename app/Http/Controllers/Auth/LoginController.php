<?php

namespace App\Http\Controllers\Auth;
use App\Helpers\CommonHelper;
use App\User;
use Auth;
use Session;
use App\Http\Controllers\Controller;
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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';
    protected function authenticated($request, $user){


        if($user->acc_type === 'master')
        {
            return redirect()->intended('/dMaster');
        }
        else if($user->acc_type === 'client')
        {
            return redirect()->intended('/dClient');
        }
        else if($user->acc_type === 'company')
        {
            return redirect()->intended('/dCompany');
        }

      
        else
        {
           
            Session::put('run_company',Auth::user()->company_id);

            return redirect()->intended('/d?pageType=0&&parentCode=0');

            CommonHelper::settings();
        }
    }
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
