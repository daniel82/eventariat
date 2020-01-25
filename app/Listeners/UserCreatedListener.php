<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class UserCreatedListener
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
     * @param  UserCreatedEvent  $event
     * @return void
     */
    public function handle(UserCreatedEvent $event)
    {
        if ( isset($event->user->email) && trim($event->user->email) )
        {
            $email = $event->user->email;
            Log::info("send notification to: ".$email );
            Mail::to($email)->send( new \App\Mail\NewUserCreatedMail( $event->user ) );
        }
        else
        {
            Log::info("missing field email for user: ".$user->id);
        }

    }
}
