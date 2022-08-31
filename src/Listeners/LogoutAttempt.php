<?php

namespace Administration\Listeners;

use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;

class LogoutAttempt
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Logout $event)
    {
      $id =$event->user->id;
      $file=$id.'.blade.php';
      File::delete(resource_path() . '/views/user_menu/'.$file);
    }
}
