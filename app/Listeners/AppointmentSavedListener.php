<?php

namespace App\Listeners;

use App\Events\AppointmentSavedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AppointmentSavedListener
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
     * @param  AppointmentSaved  $event
     * @return void
     */
    public function handle(AppointmentSavedEvent $event)
    {

        if ( isset($event->appointment->user->email) &&  $event->appointment->user->email )
        {
            Log::info("send notification to: ".$event->appointment->user->email);
            Mail::to($event->appointment->user->email)->send( new \App\Mail\AppointmentSaved($event->appointment) );
        }
    }
}
