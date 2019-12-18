<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentSaved extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $user;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $appointment )
    {
      $this->appointment = $appointment;
      $this->user = $appointment->user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

      $subject = "Terminbenachrichtiung";

      return $this->subject($subject)->markdown('emails.appointment-saved');
    }
}

