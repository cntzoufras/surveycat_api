<?php

namespace App\Listeners;

use App\Events\PasswordResetRequested;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\UserPasswordReset;
use Illuminate\Support\Facades\Notification;

class SendPasswordResetNotification {

    /**
     * Create the event listener.
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void {
        Notification::send($event->user, new UserPasswordReset($event->user, $event->token));
    }
}
