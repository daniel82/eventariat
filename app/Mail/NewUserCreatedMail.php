<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewUserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $user )
    {
      $this->user = $user;
      $this->password = \App\User::makeDefaultPassword($user->last_name);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      $subject = "Dienstplan Pirna: Zugangsdaten";
      return $this->subject($subject)->markdown('emails.user-created');
    }
}

