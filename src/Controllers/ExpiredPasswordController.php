<?php

namespace Administration\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Administration\Services\PasswordPolicyService;
use Administration\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;

class ExpiredPasswordController extends Controller
{
    use ResetsPasswords;

    public function expired()
    {
        return view('Administration::auth.passwords.expire');
    }

    public function postExpired(Request $request)
    {

        // Checking current password
        if (!Hash::check($request->current_password, $request->user()->password)) {
            return redirect()->back()->with(['alert-type' => 'error', 'message' => 'Current password is not correct']);
        }
        $pc = new PasswordPolicyService($request->user());
        $is_recently_used = $pc->isRecentlyUsedPassword($request->password);
        if($is_recently_used){
            return redirect()->back()->with(['alert-type' => 'error', 'message' => 'Not allowed to add 24 previous passwords as new password']);
        }

        $pwd =bcrypt($request->password);
        $request->user()->update([
            'password' => $pwd,
            'password_changed_at' => Carbon::now()->toDateTimeString()
        ]);

        $pc->passwordChangeProcess($request->password);
        return redirect()->to('/home')->with(['status' => 'Password changed successfully']);
    }

    public function emailVerification(Request $request)
    {
        if (!empty($_REQUEST['email'])) {
            $user_email = $_REQUEST['email'];
            $users = User::where('email', '=', "$user_email")->get();
            if ($users->count() == 1) {
                return json_encode(true);
            } else {
                return json_encode("We can't find a user with that e-mail address.");
            }
        }
    }

    public function recentPasswordValid(Request $request){

        if(isset($request->email)){
            $user = User::where('email',$request->email)->first();
            $passwordPolicyService = new PasswordPolicyService($user);
            $is_recently_used = $passwordPolicyService->isRecentlyUsedPassword($password);
            if(!$is_recently_used){
                return 'true';
            }
        }
        $password = $request->password;
        return 'false';
    }
}
