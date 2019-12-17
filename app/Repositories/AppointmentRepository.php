<?php

namespace App\Repositories;

use App\Appointment;
use App\Location;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AppointmentRepository
{

  public function index( Request $request )
  {

    $df = new Carbon('first day of this month');
    $date_from = date("Y-m-d", strtotime('monday this week', $df->timestamp ) );


    $dt = new Carbon('last day of this month');
    $date_to = date("Y-m-d", strtotime('sunday this week', $dt->timestamp ) );

    $data["today"]  = date("Y-m-d");
    $data["ev_app_data"] =
    [
      "date_from"       => $date_from,
      "date_to"         => $date_to,
      "today"           => $data["today"],
      "location_ids"    => [],
      "user_ids"        => [],
      "items"           => [],
      "busy"            => "",
      "actions_toggled" => false,
      "showLayer"       => false,
      "message"         => null,

      "appointment_id"  => "",
      "location_id"     => "",
      "user_id"         => "",
      "type"            => "4",
      "description"     => "",
      "apt_date_from"   => $data["today"],
      "apt_date_to"     => $data["today"],
      "time_from"       => "08:00",
      "time_to"         => "16:30",
      "note"            => "",
    ];

    $data["locations"]   = Location::all();

    // TODO
    $user = User::find(1);
    $data["users"]      = ( $user->can_see_other_appointments ) ? User::all() : collect([$user]);

    // TODO if is admin
    $data["hours"]   = $this->getHours();


    return $data;
  }


  public function getHours()
  {
    $hours = collect();
    $minutes = ["00", "15", "30", "45"];
    foreach ( range(0,24) as $key => $hour)
    {
      $hour = ($hour<10) ? "0".$hour : $hour;

      foreach ($minutes as $quarter)
      {
        $hours->put( $hour.":".$quarter , $hour.":".$quarter );
      }
    }

    return $hours;
  }


}