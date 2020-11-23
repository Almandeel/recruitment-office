<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserLoggedOut
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
     * @param  Logout'  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        if(!auth()->guard('office')->check()){
            $log = logging('logout', $event->user, $event->user->getClient());
        }
    }
}
