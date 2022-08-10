<?php

namespace Administration\Controllers;

use Administration\Models\Menu;
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

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::whereEmail($request->email)->first();

        if (!empty($user)) {
           if ($user->login_attempts >= 3){
               session()->flash('message', 'Your account has been blocked du to too many wrong login attempts.Please contact system administrator');
               session()->flash('alert-type', 'error');
               return redirect()->to('/login');
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

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $menu_id  = $this->guard()->user()->landing_page;
        if (!empty($menu_id)) {
            $menu = Menu::where('id','=',$menu_id)->first();
            $url = $menu->url;
            return redirect(route($url));
        }

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    protected function logout()
    {
        Session::flush();
        return redirect('/');
    }

}
