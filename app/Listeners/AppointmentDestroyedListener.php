<?php

namespace App\Listeners;

use App\Events\AppointmentDestroyedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class AppointmentDestroyedListener
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
     * @param  AppointmentDestroyedEvent  $event
     * @return void
     */
    public function handle(AppointmentDestroyedEvent $event)
    {

        if ( isset($event->appointment->user->email) &&  $event->appointment->user->email )
        {
            Log::info("send notification to: ".$event->appointment->user->email);
            Mail::to($event->appointment->user->email)->send( new \App\Mail\AppointmentDestroyed($event->appointment) );
        }
    }
}
