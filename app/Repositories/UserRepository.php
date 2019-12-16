<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
  public function getFormData( User $user )
  {
    $data["object"] = $user;
    $data["appointment_types"] = ($user->appointment_types ) ? unserialize( $user->appointment_types ) : [];
    //
    // dd($user);

    return $data;
  }

}