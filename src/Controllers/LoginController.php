<?php

namespace Administration\Controllers;

use Administration\Models\Role;
use Administration\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {

        $this->validateLogin($request);

        $user = User::whereEmail($request->email)->first();
        
        if (!empty($user)) {
           if ($user->login_attempts >= config('auth.invalid_login_count')){
               session()->flash('message', 'Your account has been blocked due to too many wrong login attempts.Please contact system administrator');
               session()->flash('alert-type', 'error');
               return redirect()->to('/login');
           }
        }
        if (!empty($user)) {
            $last_login = new Carbon(($user->last_login) ? $user->last_login : $user->created_at);
            if (Carbon::now()->diffInDays($last_login) >= config('auth.user_expires_days')) {
                session()->flash('message', 'Your session has expired because your account is disabled');
                session()->flash('alert-type', 'error');
                return redirect('/login');
            }
        }
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }


        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
