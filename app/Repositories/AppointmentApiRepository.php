<?php

namespace App\Repositories;

use App\Appointment;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AppointmentApiRepository
{

  public function index( Request $request )
  {

    $date_from      = $request->get("date_from" );
    $date_to        = $request->get("date_to");
    $user_ids       = $request->get("users");
    $location_ids   = $request->get("locations");
    $nav            = $request->get("nav", null);

    if ( $nav )
    {
      if ( $nav === "next" && $date_to )
      {
        $df = Carbon::create($date_to);
        $date_from = date("Y-m-d", strtotime('monday this week', $df->timestamp ) );
        $next_day = $df->add(1, "day");
        // dd($next_day);
        // $dt = new Carbon('last day of this month', $next_day->timestamp );
        // $last_day = date("Y-m-d", strtotime('last day of this month', $next_day->timestamp ) );
        $last_day_time = strtotime('last day of this month', $next_day->timestamp );
        $date_to = date("Y-m-d", strtotime('sunday this week', $last_day_time ) );
        // dd($date_to);
      }
      elseif ( $nav === "prev" && $date_from )
      {
        $df = Carbon::create($date_from);
        $prev_day = $df->sub(1, "day");

        $first_day_time = strtotime('first day of this month', $prev_day->timestamp );
        $date_from = date("Y-m-d", strtotime('monday this week', $first_day_time ) );

        $last_day_time = strtotime('last day of this month', $first_day_time );
        $date_to = date("Y-m-d", strtotime('sunday this week', $last_day_time ) );
      }
      elseif ( $nav === "today"  )
      {
        $df = Carbon::now();
        $date_from = date("Y-m-d", strtotime('monday this week', $df->timestamp ) );
        $last_day_time = strtotime('last day of this month', $df->timestamp );
        $date_to = date("Y-m-d", strtotime('sunday this week', $last_day_time ) );
      }
    }

    if ( !$date_from )
    {
      $df = new Carbon('first day of this month');
      $date_from = date("Y-m-d", strtotime('monday this week', $df->timestamp ) );


      $dt = new Carbon('last day of this month');
      $date_to = date("Y-m-d", strtotime('sunday this week', $dt->timestamp ) );
    }


    $from_month      = Carbon::create($date_from)->month;
    $to_month        = Carbon::create($date_to)->month;
    $period          = new CarbonPeriod($date_from, '1 day', $date_to);

    $data =
    [
      "date_from" => $date_from,
      "date_to"   => $date_to,
      "items"     => []
    ];

    // dd($from_month);
    // birthdays
    $users          = User::birthdate($from_month, $to_month)->get();
//
    // dd($users);

    // events without location
    $top_events     = Appointment::events()->noLocation()->dateFromBetween($date_from, $date_to)->orderBy("date_from")->get();

    // FeWo
    $fewo_events    = Appointment::holidayFlat()->dateFromBetween($date_from, $date_to)->orderBy("date_from", "ASC")->orderBy("location_id", "ASC")->get();

    // the users leave days
    $leaveDays      = Appointment::leaveDays()->userIds($user_ids)->dateFromBetween($date_from, $date_to)->orderBy("date_from", "ASC")->orderBy("user_id", "ASC")->get();

    // // work
    $items=[];
    foreach ($period as $key => $date)
    {

      $the_date               = $date->format("Y-m-d");
      $the_date_start         = $date->format("Y-m-d")." 00:00:00";
      $the_date_end           = $date->format("Y-m-d")." 24:00:00";

      // $data[$the_date]["day"] = $date->formatLocalized("% %d.%m");
      $items[$the_date]["date"]  = $date->isoFormat('dd. D.M');
      // $data[$the_date]["date"]  = $date->shortLocaleDayOfWeek; //$date->isoFormat('dd. D.M');
      $items[$the_date]["appointments"] = collect();


      // Urlaub
      $leave_dates = $leaveDays->filter(function ($appointment, $key) use($the_date)
      {
        return ($appointment->date_from >= $the_date && $appointment->date_to <= $the_date);
      });
      if ( $leave_dates )
      {
       $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $leave_dates );
      }


      // Ereignisse ohne Lokalitaet
      if ( $events = $top_events->whereBetween("date_from", [$the_date_start, $the_date_end] ) )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->eventAppointmentsToJson($events) );
      }

      // FeWo
      $fewo_dates = $fewo_events->filter(function ($appointment, $key) use($the_date)
      {
        return ($appointment->date_from >= $the_date && $appointment->date_to <= $the_date);
      });
      if ( $fewo_dates )
      {
       $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $fewo_dates );
      }


      // Geburtstage
      $birthday_kids = $users->filter(function ($user, $key) use($the_date)
      {
        return ( substr($user->birthdate, 5, 5) ) == substr($the_date, 5, 5) ;
      });

      if ( $birthday_kids )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->brithdayKidsToJson($birthday_kids, $the_date) );
      }


      // Normale Arbeit
      if ( $appointments = Appointment::work()->userIds($user_ids)->locationIds($location_ids)->dateFrom($the_date)->orderBy("date_from", "ASC")->orderBy("location_id", "ASC")->get() )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->workAppointmentsToJson($appointments) );
      }

      # code...
    }

    $data["items"] = $items;

    return $data;
  }


  public function brithdayKidsToJson( $users, $date )
  {
    $birthdates  = [];

    foreach ($users as $key => $user)
    {
      // $age = Carbon::parse($user->birthdate)->diffInYears($date);

      $birthdates[] =
      [
        "type"           => "birthday",
        "date_from"      => $date,
        "date_to"        => $date,
        "time"           => null,
        "description"    => $user->getCalendarName(),
        "age"            => Carbon::parse($user->birthdate)->diffInYears($date)
      ];
    }



    return $birthdates;
  }


  public function eventAppointmentsToJson( $appointments )
  {

   $items = [];

   foreach ($appointments as $key => $appointment)
   {
      $items[] =
      [
        "type"           => "event",
        "date_from"      => $appointment->date_from,
        "date_to"        => $appointment->date_to,
        "time"           => date("H:i", strtotime($appointment->date_from) ),
        "description"    => $appointment->description,
      ];
   }

   return $items;
  }

  public function workAppointmentsToJson( $appointments )
  {

   $items = [];

   foreach ($appointments as $key => $appointment)
   {
      $items[] =
      [
        "type"           => "work",
        "date_from"      => $appointment->date_from,
        "date_to"        => $appointment->date_to,
        "time"           => date("H:i", strtotime($appointment->date_from) ),
        "description"    => $appointment->user->getCalendarName(),
        "location_id"    => $appointment->location_id,
      ];
   }

   return $items;
  }


}