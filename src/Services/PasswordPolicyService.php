<?php

namespace Administration\Services;

use Administration\Models\PasswordHistory;
use Administration\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordPolicyService
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        Log::channel('password')->info("Password Policy Service Initialed for $user->id");
    }

    protected function userId()
    {
        return $this->user->id;
    }

    protected function passwordHistoryLimit()
    {
        return config('auth.password_history_num');
    }

    public function currentPasswordCount()
    {
        return PasswordHistory::whereUserId($this->userId())->count();
    }

    public function isRecentlyUsedPassword($password)
    {
        $passwords = $this->lastStoredPasswords($this->userId());
        foreach ($passwords as $old_password) {
            if (Hash::check($password, $old_password)) {
                return true;
            }
        }
        return false;
    }

    protected function lastStoredPasswords()
    {
        return PasswordHistory::whereUserId($this->userId())->pluck('password');
    }

    protected function savePassword($password)
    {
        $userId = $this->userId();
        $history = PasswordHistory::create(['user_id' => $this->userId(), 'password' => Hash::make($password)]);
        Log::channel('password')->info("New Password Change $userId");
    }

    protected function deleteLastPassword()
    {
        $userId = $this->userId();
        $lastPass = PasswordHistory::whereUserId($this->userId())->oldest()->first();
        $lastPass->delete();
        Log::channel('password')->info("Last password Deleted $userId");
    }

    public function passwordChangeProcess($password)
    {
        $userId = $this->userId();
        $currentPasswordCount = $this->currentPasswordCount();
        $passwordHistoryLimit = $this->passwordHistoryLimit();
        Log::channel('password')->info("USER ID : $userId  CURRENT PASSWORD COUNT : $currentPasswordCount PASSWORD HISTORY LIMIT $passwordHistoryLimit");
        if ($currentPasswordCount > $passwordHistoryLimit) {
            $this->deleteLastPassword();
            $this->savePassword($password);
        } else {
            $this->savePassword($password);
        }
    }

    protected function regexMatch($password)
    {
        return preg_match("/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Z])(?=.*[a-z])[a-zA-Z0-9!@#$%^&*]{8,16}$/", $password);
    }

    public function minimumPasswordAge()
    {
        $user = User::find($this->userId());
        $password_changed_at = new Carbon(($user->password_changed_at) ? $user->password_changed_at : $user->created_at);

        if (Carbon::now()->diffInDays($password_changed_at) > config('auth.password_minimum_days')) {
            return true;
        }
        return false;
    }
}
