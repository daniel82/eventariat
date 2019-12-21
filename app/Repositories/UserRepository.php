<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Appointment;
use App\ShiftRequest;
use App\User;
use Carbon\Carbon;

class UserRepository
{
  public function getFormData( User $user )
  {
    $data["object"] = $user;
    $data["appointment_types"] = ($user->appointment_types ) ? unserialize( $user->appointment_types ) : [];

    $data["leave_days"] = Appointment::leaveDays()->userIds([$user->id])->orderBy("date_from", "DESC")->get();

    foreach ( $data["leave_days"] as $key => $period )
    {
      $date1 = Carbon::create($period->date_from);
      $date2 = Carbon::create($period->date_to);
      $period->diffInDays = $date1->diffInDays($date2);
    }

    $data["shift_requests"] = $user->shiftRequests;

    $types =  config("shift-request.type");
    foreach ($data["shift_requests"] as $key => &$item)
    {
      $item->type_hr = ( isset($types[$item->type]) ) ? $types[$item->type]["text"] : null;
    }


    return $data;
  }


  public function sanitizeRequest( Request $request )
  {
    $request["appointment_types"] = serialize( $request->get("appointment_types", []) );
    $request["can_see_other_appointments"] = $request->get("can_see_other_appointments", 0);

    return $request;
  }


  public function valildatePasswords( Request $request )
  {
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