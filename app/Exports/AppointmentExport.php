<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use App\Api\Yr;
use App\Appointment;
use App\Location;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AppointmentExport
{
  public function __construct(){}

  public function index( Request $request )
  {
    $date_from      = $request->get("date_from" );
    $date_to        = $request->get("date_to");
    $user_ids       = $request->get("users");
    $location_ids   = $request->get("locations");
    $nav            = $request->get("nav", null);

    $user = \Auth::user();

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
    $users = collect();
    if ( $user->canSee("events") )
    {
      $users = User::birthdate($from_month, $to_month)->get();
    }


    // events without location
    $top_events = collect();
    if ( $user->canSee("events") )
    {
      $top_events = Appointment::events()->noLocation()->dateFromBetween($date_from, $date_to)->orderBy("date_from")->get();
    }


    // FeWo
    $fewo_events = collect();
    if ( $user->canSee("fewo_dates") )
    {
      $fewo_events = Appointment::holidayFlat()->dateFromBetween($date_from, $date_to)->orderBy("date_from", "ASC")->orderBy("location_id", "ASC")->get();
    }


    $leave_days = collect();
    if ( $user->canSee("leave_days") )
    {
      // TODO improve dateFromBetween
      $leave_days = Appointment::leaveDays()->userIds($user_ids)->dateFromBetween($date_from, $date_to)->orderBy("date_from", "ASC")->orderBy("user_id", "ASC")->get();
    }



    $various_events = Appointment::various()->userIds($user_ids)->dateFromBetween($date_from, $date_to)->orderBy("date_from", "ASC")->orderBy("user_id", "ASC")->get();

    // Log::debug($various_events);


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

      $items[$the_date]["forecast"] = \App\WeatherForecast::getAsJsonByDate( $the_date );


      // Urlaub
      //
      // $leave_dates = $leave_days->filter(function ($appointment, $key) use($the_date_start, $the_date_end)
      //
      // dump($the_date_start);
      $leave_dates = $leave_days->filter(function ($appointment, $key) use($the_date, $the_date_start)
      {
        return ($appointment->date_from <= $the_date_start && $appointment->date_to >= $the_date);
      });

      // dump($leave_dates);



      if ( $leave_dates && $leave_dates->count() )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->leaveDayAppointmentsToJson( $leave_dates) );
      }


      // Ereignisse ohne Lokalitaet
      $event_dates = $top_events->filter(function ($appointment, $key) use($the_date_start, $the_date)
      {
        return ($appointment->date_from <= $the_date_start && $appointment->date_to >= $the_date);
      });
      if ( $event_dates )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->eventAppointmentsToJson($event_dates) );
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


      // Various

      // $various_dates = $various_events->whereBetween("date_from", [$the_date_start, $the_date_end] );
      $various_dates = $various_events->filter(function ($appointment, $key) use($the_date_start, $the_date)
      {
        return ($appointment->date_from <= $the_date_start && $appointment->date_to >= $the_date);
      });

      if ( $various_dates )
      {
       $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->workAppointmentsToJson($various_dates) );
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



      if ( !$user->can_see_other_appointments )
      {
        $user_ids  = [$user->id];
      }
      // Normale Arbeit
      if ( $appointments = Appointment::work()->userIds($user_ids)->locationIds($location_ids)->dateFrom($the_date)->orderBy("date_from", "ASC")->orderBy("location_id", "ASC")->get() )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->workAppointmentsToJson($appointments) );
      }

      // Freier Tag
      if ( $appointments = Appointment::free()->userIds($user_ids)->dateFrom($the_date)->orderBy("date_from", "ASC")->orderBy("user_id", "ASC")->get() )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->freeAppointmentsToJson($appointments) );
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
        "type_class"     => "birthday",
        "date_from"      => $date,
        "date_to"        => $date,
        "time"           => null,
        "title"          => $user->getCalendarName(),
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
        "id"             => $appointment->id,
        "type_class"     => "event",
        "date_from"      => formatDate( $appointment->date_from, "Y-m-d" ),
        "time_from"      => formatDate( $appointment->date_from, "H:i" ),
        "date_to"        => formatDate( $appointment->date_to, "Y-m-d" ),
        "time_to"        => formatDate( $appointment->date_to, "H:i" ),
        "title"          => $appointment->description,
        "description"    => $appointment->description,
        "location_id"    => $appointment->location_id,
        "user_id"        => $appointment->user_id,
        "type"           => $appointment->type,
        "note"           => $appointment->note,
      ];
   }

   return $items;
  }

  public function leaveDayAppointmentsToJson( $appointments )
  {

   $items = [];

   foreach ($appointments as $key => $appointment)
   {
      $title = ($appointment->user ) ? $appointment->user->getCalendarName() : null;

      $items[] =
      [
        "id"             => $appointment->id,
        "type_class"     => "leave-day",
        "date_from"      => formatDate( $appointment->date_from, $format="Y-m-d" ),
        "time_from"      => formatDate( $appointment->date_from, $format="H:i" ),
        "date_to"        => formatDate( $appointment->date_to, $format="Y-m-d" ),
        "time_to"        => formatDate( $appointment->date_to, $format="H:i" ),
        "title"          => $title,
        "description"    => null,
        "location_id"    => null,
        "user_id"        => $appointment->user_id,
        "type"           => $appointment->type,
        "note"           => $appointment->note,
      ];
   }

   return $items;
  }


  public function freeAppointmentsToJson( $appointments )
  {

   $items = [];

   foreach ($appointments as $key => $appointment)
   {
      $title = ($appointment->user ) ? $appointment->user->getCalendarName(). "(frei)" : null;

      $items[] =
      [
        "id"             => $appointment->id,
        "type_class"     => "free-day",
        "date_from"      => formatDate( $appointment->date_from, $format="Y-m-d" ),
        "time_from"      => formatDate( $appointment->date_from, $format="H:i" ),
        "date_to"        => formatDate( $appointment->date_to, $format="Y-m-d" ),
        "time_to"        => formatDate( $appointment->date_to, $format="H:i" ),
        "title"          => $title,
        "description"    => null,
        "location_id"    => null,
        "user_id"        => $appointment->user_id,
        "type"           => $appointment->type,
        "note"           => $appointment->note,
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
        "id"             => $appointment->id,
        "type_class"     => "work",
        "date_from"      => date("Y-m-d", strtotime($appointment->date_from) ),
        "time_from"      => date("H:i", strtotime($appointment->date_from) ),
        "date_to"        => date("Y-m-d", strtotime($appointment->date_to) ),
        "time_to"        => date("H:i", strtotime($appointment->date_to) ),
        "title"          => ($appointment->user ) ? $appointment->user->getCalendarName() : null,

        "description"    => $appointment->description,
        "location_id"    => $appointment->location_id,

        "user_id"        => $appointment->user_id,
        "type"           => $appointment->type,
        "note"           => $appointment->note,

        "tooltip_title"    => ($appointment->user ) ? $appointment->user->getFullName() : null,
        "tooltip_location" => $appointment->location->name,
      ];
   }

   return $items;
  }


  // public function variousAppointmentsToJson( $appointments )
  // {

  //  $items = [];

  //  foreach ($appointments as $key => $appointment)
  //  {
  //     $items[] =
  //     [
  //       "id" => $appointment->id,
  //       "type_class"     => "work",
  //       "date_from"      => date("Y-m-d", strtotime($appointment->date_from) ),
  //       "time_from"      => date("H:i", strtotime($appointment->date_from) ),
  //       "date_to"        => date("Y-m-d", strtotime($appointment->date_to) ),
  //       "time_to"        => date("H:i", strtotime($appointment->date_to) ),
  //       "title"          => ($appointment->user ) ? $appointment->user->getCalendarName() : null,
  //       "description"    => $appointment->description,
  //       "location_id"    => $appointment->location_id,
  //       "user_id"        => $appointment->user_id,
  //       "type"           => $appointment->type,
  //       "note"           => $appointment->note,
  //     ];
  //  }

  //  return $items;
  // }


}