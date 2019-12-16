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

    $date_from = "2019-12-30";
    $date_to = "2020-02-02";

    $data["ev_app_data"] =
    [
      "date_from"    => $date_from,
      "date_to"      => $date_to,
      "today"        => date("Y-m-d"),
      "location_ids" => [],
      "user_ids"     => [],
      "items"        => [],
      "busy"         => "",
    ];

    // TODO
    $user = User::find(1);
    $data["users"]      = ( $user->can_see_other_appointments ) ? User::all() : collect([$user]);
    $data["locations"]   = Location::all();


    return $data;
  }


}