<?php

namespace Administration\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;

class LoginFailedAttempt
{
    /**
     * Handle the event.
     *
     * @param Login $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $user = $event->user;
        if (!empty($user)) {
            $user->login_attempts = $user->login_attempts + 1;
            $user->save();
        }

    }


}
