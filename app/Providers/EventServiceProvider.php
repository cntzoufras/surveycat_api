<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Listeners\SendWelcomeEmailListener;
use App\Events\PasswordResetRequested;
use App\Listeners\SendPasswordResetNotification;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class             => [
            SendEmailVerificationNotification::class, // Laravel's default verification email
        ],
        UserRegistered::class         => [
            SendWelcomeEmailListener::class,
        ],
        PasswordResetRequested::class => [
            SendPasswordResetNotification::class,
        ],
    ];


    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        //
    }
}
