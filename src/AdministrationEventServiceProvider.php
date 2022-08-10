<?php

namespace Administration;

use Administration\Listeners\LoginAttempt;
use Administration\Listeners\LoginAttempting;
use Administration\Listeners\LoginFailedAttempt;
use Administration\Listeners\LogoutAttempt;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class AdministrationEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Login::class=>[
            LoginAttempt::class,
        ],
        Logout::class=>[
            LogoutAttempt::class,
        ],
        Failed::class=>[
            LoginFailedAttempt::class,
        ],Attempting::class=>[
            LoginAttempting::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
