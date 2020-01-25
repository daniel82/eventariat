<?php

namespace App\Repositories;

use App\Api\Yr;
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
    $df             = new Carbon('first day of this month');
    $date_from      = date("Y-m-d", strtotime('monday this week', $df->timestamp ) );
    // $date_from      = "2019-12-30";
    $dt             = new Carbon('last day of this month');
    $date_to        = date("Y-m-d", strtotime('sunday this week', $dt->timestamp ) );
    // $date_to        = "2020-02-02";
    $data["today"]  = date("Y-m-d");

    // TODO find logic when update forecast
    $yr = new Yr();
    $yr->getForecast($data["today"]);

    $data["user"]       = \Auth::user();
    $data["users"]      = ( $data["user"]->can_see_other_appointments ) ? User::all() : collect([$data["user"]]);

  // [ "id" => 4, "text"=>"Arbeit" ],
  // [ "id" => 1, "text"=>"Urlaub" ],
  // [ "id" => 2, "text"=>"Ereignis"],
  // [ "id" => 3, "text"=>"Ferienwohnung"],
  // // [ "id" => 5, "text"=>"Sonstiges"],
  // [ "id" => 6, "text"=>"Frei"],

    $data["ev_app_data"] =
    [
      "pdf_url"         => action("AppointmentController@pdf"),
      "date_from"       => $date_from,
      "date_to"         => $date_to,
      "weeks"           => [],
      "today"           => $data["today"],
      "location_ids"    => [],
      "appointment_types"=> [1,2,4,5,6],
      "user_ids"        => ($data["users"]->count() === 1) ? [$data["users"]->first()->id] : [],
      "items"           => [],
      "busy"            => "",
      "actions_toggled" => false,
      "showLayer"       => false,
      "message"         => null,
      "message_type"    => null,
      "is_admin"        => $data["user"]->isAdmin(),
      // "is_admin"        => false, // static test value
      "current_user"    => $data["user"]->id,
      "col_counter"     => 0,

      // "current_user"    => 4,  // static test value

      "appointment_id"  => "",
      "location_id"     => "",
      "user_id"         => "",
      "type"            => "4",
      "description"     => "",
      "apt_date_from"   => $data["today"],
      "apt_date_to"     => $data["today"],
      "time_from"       => "08:00",
      "time_to"         => "16:30",
      "default_time_from"=> "08:00",
      "default_time_to"  => "16:30",
      "note"            => "",

      "tooltip_x"         => 0,
      "tooltip_y"         => 0,
      "tooltip_title"     => '',
      "tooltip_time"      => '',
      "tooltip_location"  => '',
      "tooltip_info"      => '',
      "tooltip_leave_days"=> '',
      "tooltip_work_load" => '',
      "ajax_active"       => false,
    ];

    $data["locations"]   = Location::all();
    $data["appointment_types"]   = config("appointment.type");


    // TODO if is admin
    $data["hours"]   = $this->getHours();

    // dd($data["hours"]);


    return $data;
  }


  public function getHours()
  {
    $hours = collect();
    $hours->put( "" , "---" );
    $minutes = ["00", "15", "30", "45"];
    foreach ( range(0,23) as $key => $hour)
    {
      $hour = ($hour<10) ? "0".$hour : $hour;

      foreach ($minutes as $quarter)
      {
        $hours->put( $hour.":".$quarter , $hour.":".$quarter );
      }
    }

    $hours->put( "23:59" , "23:59" );

    return $hours;
  }


}