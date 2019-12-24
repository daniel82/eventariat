<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ShiftRequestCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shiftRequest;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $shiftRequest )
    {
      $this->shiftRequest = $shiftRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $subject = "Neuer Antrag";
      return $this->subject($subject)->markdown('emails.shift-request-created');
    }
}

