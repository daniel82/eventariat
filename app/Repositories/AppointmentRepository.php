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
    // $data["users"]      = ( $data["user"]->can_see_other_appointments ) ? User::orderBy("employment")->orderBy("first_name")->get() : collect([$data["user"]]);
    $data["users"] = collect();
    $data["employment_types"] = config("users.employment");

    // if user can not see other appointmens
    if ( !$data["user"]->can_see_other_appointments  )
    {
      // set only this user to users list
      $data["users"][ $data["user"]->employment ]  = collect([$data["user"]]);
    }
    else
    {
      // get all users ordered dy employment
      foreach ( $data["employment_types"] as $employment => $title)
      {
        if ( $items = User::where("employment",$employment)->orderBy("first_name")->get() )
        {
          $data["users"][$employment] = $items;
        }
      }
    }


    $data["ev_app_data"] =
    [
      "pdf_url"         => action("AppointmentController@pdf"),
      "date_from"       => $date_from,
      "date_to"         => $date_to,
      "date_from_hr"    => formatDate($date_from, "d.m.y"),
      "date_to_hr"      => formatDate($date_to, "d.m.y"),
      "weeks"           => [],
      "today"           => $data["today"],
      "location_ids"    => [],
      "appointment_types"=> [1,2,4,5,6,7,8],
      "user_ids"        => ($data["users"]->count() === 1) ? [$data["user"]->id] : [],
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
      "recurring"       => "0",
      "future_events"   => null,
      "apt_date_from"   => $data["today"],
      "apt_date_to"     => $data["today"],
      "apt_repeat_until"=> null,
      "time_from"       => "08:00",
      "time_to"         => "16:30",
      "default_time_from"=> "08:00",
      "default_time_to"  => "16:30",
      "note"            => "",
      "requested_nav"     => "",

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


    $data["from_hours"]   = getHours("from");
    $data["to_hours"]   = getHours("to");


    return $data;
  }



}