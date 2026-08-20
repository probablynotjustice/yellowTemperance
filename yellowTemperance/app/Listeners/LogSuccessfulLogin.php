<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ActivityLog;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */


    public function handle(Login $event): void
    {
        ActivityLog::record(
            $event->user,
            $event->user,
            'logged_in',
            "User '{$event->user->name}' logged in.",
            null,
            null
        );
    }

}
