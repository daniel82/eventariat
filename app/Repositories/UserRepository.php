<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Appointment;
use App\Location;
use App\ShiftRequest;
use App\User;
use Carbon\Carbon;

class UserRepository
{
  public function diffInDaysNetto( int $diffInDays )
  {

    $netto_days = config("appointment.weekend_map");

    $diffInDays_netto = $diffInDays;
    foreach ($netto_days as $key => $value)
    {
      if ( $diffInDays >= $value["days"] )
      {
        $diffInDays_netto = $diffInDays - $value["weekend"];
      }
    }

    return $diffInDays_netto;
  }


  public function getFormData( User $user )
  {
    $data["object"] = $user;
    $data["appointment_types"] = $user->appointment_types;

    $data["leave_days"]      = Appointment::leaveDays()->userIds([$user->id])->orderBy("date_from", "DESC")->get();
    $data["locations"]       = Location::all();
    $data["user_locations"]  = $user->locations()->pluck("id");
    $data["ev_app_data"]["employment"] = $user->employment;

    foreach ( $data["leave_days"] as $key => $appointment )
    {
      $date1 = Carbon::create($appointment->date_from);
      $date2 = Carbon::create($appointment->date_to);

      $diffInDays = ($date1->diffInDays($date2)+1);

      $appointment->diffInDays       = $diffInDays;
      $appointment->diffInDaysNetto  = $this->diffInDaysNetto($diffInDays);

    }

    $data["shift_requests"] = $user->shiftRequests;

    $types =  config("shift-request.type");
    foreach ($data["shift_requests"] as $key => &$item)
    {
      $item->type_hr = ( isset($types[$item->type]) ) ? $types[$item->type]["text"] : null;
    }


    return $data;
  }


  public function syncTags( Request $request, User $user )
  {
    if ( $location_ids = $request->get("location_ids") )
    {
      $user->locations()->sync( $location_ids );
    }
  }


  public function sanitizeRequest( Request $request, $method=null )
  {
    $request["appointment_types"] = serialize( $request->get("appointment_types", []) );
    $request["can_see_other_appointments"] = $request->get("can_see_other_appointments", 0);

    if ( $pw = $this->valildatePasswords($request, $method ) )
    {
      $request["password"] = $pw;
    }
    else
    {
      unset($request["password"]);
    }


    return $request;
  }


  public function valildatePasswords( Request $request, $method=null )
  {
    $password = $request->get("password");
    $new_password = $request->get("password_confirmation");

    if ( $password && $password === $new_password )
    {
      return bcrypt($new_password);
    }
    elseif ( !$password && $method === "store" or $request->get("new_password") )
    {
      return bcrypt( User::makeDefaultPassword( $request->get("last_name") ) );
    }
    else
    {
      return null;
    }
  }

}