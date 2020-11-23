<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLoggedIn
{
    /**
    * Create the event listener.
    *
    * @return void
    */
    public function __construct()
    {
        //
    }
    
    /**
    * Handle the event.
    *
    * @param  Login'  $event
    * @return void
    */
    public function handle(Login $event)
    {
        if (!auth()->guard('office')->check()) {
            $log = logging('login', $event->user, $event->user->getClient());
        }
    }
}