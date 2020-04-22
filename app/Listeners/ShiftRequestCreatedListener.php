<?php

namespace App\Listeners;

use App\Events\ShiftRequestCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class ShiftRequestCreatedListener
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
     * @param  ShiftRequestCreatedEvent  $event
     * @return void
     */
    public function handle( ShiftRequestCreatedEvent $event )
    {
        $email = "claudia@schloss-pirna.de";
        Log::info("send notification to: ".$email );
        Mail::to($email)->send( new \App\Mail\ShiftRequestCreatedMail($event->shiftRequest) );
    }
}
