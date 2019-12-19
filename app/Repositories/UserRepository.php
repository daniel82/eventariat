<?php

namespace App\Repositories;

use Illuminate\Http\Request;
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


  public function sanitizeRequest( Request $request )
  {
    $request["appointment_types"] = serialize( $request->get("appointment_types", []) );
    $request["can_see_other_appointments"] = $request->get("can_see_other_appointments", 0);

    $new_password = $request->get("new_password");
    $confirm_password = $request->get("confirm_password");
    if ( $new_password && $confirm_password && $new_password === $confirm_password )
    {
      $request["password"] = bcrypt($new_password);
      unset($request["new_password"]);
      unset($request["confirm_password"]);
    }

    return $request;
  }

}