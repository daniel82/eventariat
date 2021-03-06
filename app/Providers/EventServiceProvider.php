<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\AppointmentSavedEvent' =>
        [
            'App\Listeners\AppointmentSavedListener',
        ],
        'App\Events\AppointmentDestroyedEvent' =>
        [
            'App\Listeners\AppointmentDestroyedListener',
        ],
        'App\Events\ShiftRequestCreatedEvent' =>
        [
            'App\Listeners\ShiftRequestCreatedListener',
        ],
        'App\Events\UserCreatedEvent' =>
        [
            'App\Listeners\UserCreatedListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
