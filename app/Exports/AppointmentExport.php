<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
// use App\Api\Yr;
use App\Appointment;
use App\Location;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AppointmentExport
{
  public function __construct(){}


  // [ "id" => 4, "text"=>"Arbeit" ],
  // [ "id" => 1, "text"=>"Urlaub" ],
  // [ "id" => 2, "text"=>"Ereignis"],
  // [ "id" => 3, "text"=>"Ferienwohnung"],
  // // [ "id" => 5, "text"=>"Sonstiges"],
  // [ "id" => 6, "text"=>"Frei"],
  //

  public function includeType( $type )
  {
    return ( in_array($type, $this->type_ids) );
  }


  public function getUserObject( $user_id )
  {
    if ( is_numeric($user_id) )
    {
      if ( !isset($this->users[$user_id]) or !is_object($this->users[$user_id]) )
      {
        $this->users[$user_id] = User::find($user_id);
      }

      return $this->users[$user_id];
    }
    else
    {
      return null;
    }
  }


  public function getLocationObject( $location_id )
  {
    if ( is_numeric($location_id) )
    {
      if ( !isset($this->locations[$location_id]) or !is_object($this->locations[$location_id]) )
      {
        $this->locations[$location_id] = Location::find($location_id);
      }

      return $this->locations[$location_id];
    }
    else
    {
      return null;
    }
  }


  public function index( Request $request )
  {
    $date_from      = $request->get("date_from" );
    $date_to        = $request->get("date_to");

    $user_ids       = $request->get("users");
    $location_ids   = $request->get("locations");
    $nav  = $request->get("nav", null);

    $this->type_ids = $request->get("types", []);
    $this->users     = [];
    $this->locations = [];

    $user = \Auth::user();

    $user_locations = null;
    if ( $user->isPermanent() or $user->isTraining() )
    {
      $user_locations = $user->locations()->pluck("id");
    }


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

    // get weater forecast for whole month
    $this->weather =  \App\WeatherForecast::getByPeriod($date_from, $date_to);

    $data =
    [
      "date_from" => $date_from,
      "date_to"   => $date_to,
      "date_from_hr" => formatDate($date_from, "d.m.Y"),
      "date_to_hr"   => formatDate($date_to, "d.m.Y"),
      "items"     => [],
      "weeks"     => []
    ];

    // dd($from_month);
    // birthdays
    $users = collect();
    if ( $user->canSee("birthdates") )
    {
      $users = User::birthdate($from_month, $to_month)->get();
    }


    // events without location
    $top_events = collect();
    if ( $user->canSee("events") && $this->includeType(2) )
    {
      $top_events = Appointment::events()
                    ->noLocation()
                    ->dateFromBetween($date_from, $date_to)
                    ->orderBy("date_from")
                    ->get();
    }


    // FeWo
    $fewo_events = collect();
    if ( $user->canSee("fewo_dates") && $this->includeType(3) )
    {
      $fewo_events =  Appointment::holidayFlat()
                      ->dateFromBetween($date_from, $date_to)
                      ->orderBy("date_from", "ASC")
                      ->orderBy("location_id", "ASC")
                      ->get();
    }


    $leave_days = collect();
    if ( $user->canSee("leave_days") && $this->includeType(1) )
    {
      $leave_days = Appointment::leaveDays()
                    ->userIds($user_ids)
                    // ->dateFromBetween($date_from, $date_to)
                    ->period($date_from, $date_to)
                    ->orderBy("date_from", "ASC")
                    ->orderBy("user_id", "ASC")->get();
    }


    $sick_days = collect();
    if ( $user->canSee("sick") && $this->includeType(7) )
    {
      $sick_days = Appointment::sick()
                    ->userIds($user_ids)
                    ->period($date_from, $date_to)
                    ->orderBy("date_from", "ASC")
                    ->orderBy("user_id", "ASC")->get();
    }


    $school_days = collect();
    if ( $user->canSee("school") && $this->includeType(8) )
    {

      $school_days = Appointment::school()
                    ->userIds($user_ids)
                    // ->dateFromBetween($date_from, $date_to)
                    ->period($date_from, $date_to)
                    ->orderBy("date_from", "ASC")
                    ->orderBy("user_id", "ASC")->get();
    }



    $free_days = collect();
    if ( $user->canSee("free_days") && $this->includeType(6) )
    {
      $free_days = Appointment::free()
                    ->userIds($user_ids)
                    // ->dateFromBetween($date_from, $date_to)
                    ->period($date_from, $date_to)
                    ->orderBy("date_from", "ASC")
                    ->orderBy("user_id", "ASC")->get();
    }


    $private_dates = collect();
    if ( $user->canSee("private_dates") && $this->includeType(5) )
    {
      $private_dates = Appointment::privateDates()
                    ->userIds($user_ids)
                    ->dateFromBetween($date_from, $date_to)
                    ->orderBy("date_from", "ASC")
                    ->orderBy("user_id", "ASC")->get();
    }


    $various_events = Appointment::various()
                      ->userIds($user_ids)
                      ->dateFromBetween($date_from, $date_to)
                      ->orderBy("date_from", "ASC")
                      ->orderBy("user_id", "ASC")->get();


    // // work
    $items=[];
    foreach ($period as $key => $date)
    {
      $the_date               = $date->format("Y-m-d");
      $the_date_start         = $date->format("Y-m-d")." 00:00:00";
      $the_date_end           = $date->format("Y-m-d")." 24:00:00";

      $items[$the_date]["date"]         = $date->isoFormat('dd. D.M.');
      $items[$the_date]["appointments"] = collect();

      $week                             = $date->week();
      $items[$the_date]["week"]         = $week;
      $data["weeks"][$week]             = $week;


      $items[$the_date]["forecast"]     = ( isset($this->weather[$the_date]) && is_array($this->weather[$the_date]) ) ? $this->weather[$the_date] : null;

      // Urlaub
      $leave_dates = $leave_days->filter(function ($appointment, $key) use($the_date, $the_date_start)
      {
        return ($appointment->date_from <= $the_date_start && $appointment->date_to >= $the_date);
      });
      if ( $leave_dates && $leave_dates->count() )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->leaveDayAppointmentsToJson( $leave_dates) );
      }


      $school_dates = $school_days->filter(function ($appointment, $key) use($the_date, $the_date_start)
      {
        return ( formatDate($appointment->date_from, $format = "Y-m-d") <= $the_date && $appointment->date_to >= $the_date);
      });
      if ( $school_dates && $school_dates->count() )
      {
        // dump($the_date);
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->schoolDayAppointmentsToJson($school_dates) );
      }


      $free_dates = $free_days->filter(function ($appointment, $key) use($the_date, $the_date_start)
      {
        return (  formatDate($appointment->date_from, $format = "Y-m-d") <= $the_date && $appointment->date_to >= $the_date);
      });
      if ( $free_dates && $free_dates->count() )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->freeAppointmentsToJson( $free_dates) );
      }


      $event_dates = $top_events->filter(function ($appointment, $key) use($the_date_start, $the_date)
      {
        // "2020-02-07 00:00:00"
        // 2020-02-07 08:00:00
        // 2020-02-07 16:30:00
        return ( formatDate( $appointment->date_from,"Y-m-d") <= $the_date && $appointment->date_to >= $the_date);
      });

      // if ( $the_date == "2020-02-07")
      // {
      //   dump($the_date_start);
      //   dump($the_date);
      //   dd($event_dates);
      // }


      if ( $event_dates )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->eventAppointmentsToJson($event_dates) );
      }


      // FeWo
      $fewo_dates = $fewo_events->filter(function ($appointment, $key) use($the_date, $the_date_start)
      {
        return ( formatDate($appointment->date_from, "Y-m-d") <= $the_date_start && formatDate($appointment->date_to, "Y-m-d") >= $the_date);

      });
      if ( $fewo_dates )
      {
       $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->fewoAppointmentsToJson( $fewo_dates) );
      }


      // Various
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
        return ( substr($user->birthdate, 5, 5) ) == substr($the_date, 5, 5);
      });

      if ( $birthday_kids )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->brithdayKidsToJson($birthday_kids, $the_date) );
      }


      // private Termine
      $private_dates2 = $private_dates->filter(function ($appointment, $key) use($the_date_start, $the_date)
      {
        return ( date("Y-m-d", strtotime($appointment->date_from)) === $the_date);
      });
      if ( $private_dates2 && $private_dates2->count() )
      {
        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->privateAppointmentsToJson($private_dates2) );
      }


      // Kranktage
      $sick_dates = $sick_days->filter(function ($appointment, $key) use($the_date_start, $the_date)
      {
        return ($appointment->date_from <= $the_date_start && $appointment->date_to >= $the_date);
      });

      if ( $sick_dates && $sick_dates->count() )
      {

        $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->sickAppointmentsToJson($sick_dates) );
      }


      if ( !$user->can_see_other_appointments )
      {
        $user_ids = [$user->id];
      }


       // Normale Arbeit
      if ( $this->includeType(4) )
      {
        $appointments = Appointment::work()
                        ->userIds($user_ids)
                        ->locationIds($location_ids)
                        ->dateFrom($the_date)
                        ->orderBy("location_id", "ASC")
                        ->orderBy("date_from", "ASC")
                        ->get();

        if ( $appointments )
        {
          $items[$the_date]["appointments"] = $items[$the_date]["appointments"]->merge( $this->workAppointmentsToJson($appointments) );
        }
      }


    } // end of foreach loop



    // can only see co workers same day, same location
    if ( $user->can_see_other_appointments == 1 )
    {
      foreach ($items as $date => &$date_data)
      {
        if ( isset($date_data["appointments"]) && !empty($date_data["appointments"]) )
        {
          $location_ids = $date_data["appointments"]->where("user_id", $user->id)->where("type_class", "work")->pluck("location_id");

          if ( $user_locations )
          {
            $location_ids = $location_ids->merge($user_locations);
          }

          if ( $location_ids->count() )
          {
            // delete all where location id not in
            $date_data["appointments"] = $date_data["appointments"]->filter( function ($a, $key) use ($location_ids)
                                        {
                                          // appointment type 4 = work
                                          if ( isset($a["type"]) )
                                          {
                                            return ($a["type"] != 4 || ($a["type"] == 4 && $location_ids->contains($a["location_id"])) );
                                          }
                                          else
                                          {
                                            // Log::debug($a);
                                            return true;
                                          }

                                        })->values();

            // $date_data["appointments"] = $date_data["appointments"]->values();
          }
          else
          {
            // hide all appointments if user has items this day
            $date_data["appointments"] = $date_data["appointments"]->where("type_class", "!=", "work");
          }
        }
      }
      // filter $items
      // get date list where user has type work
      // where type = arbeit, krank, termin
      // check if user has date
    }



    $data["items"] = $items;

    // dd($data);
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
        "type_text"      => "Geburtstag",
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
        "type_text"      => $appointment->getTypeHumanReadable(),
        "note"           => $appointment->note,
        "recurring"      => $appointment->recurring,
        "repeat_until"   => $appointment->repeat_until,
      ];
   }

   return $items;
  }


  public function fewoAppointmentsToJson( $appointments )
  {

   $items = [];

   foreach ($appointments as $key => $appointment)
   {
      $items[] =
      [
        "id"             => $appointment->id,
        "type_class"     => "fewo",
        "date_from"      => formatDate( $appointment->date_from, "Y-m-d" ),
        "time_from"      => formatDate( $appointment->date_from, "H:i" ),
        "date_to"        => formatDate( $appointment->date_to, "Y-m-d" ),
        "time_to"        => formatDate( $appointment->date_to, "H:i" ),
        "title"          => "Ferienwohnung",
        "description"    => null,
        "location_id"    => $appointment->location_id,
        "user_id"        => $appointment->user_id,
        "type"           => $appointment->type,
        "type_text"      => $appointment->getTypeHumanReadable(),
        "note"           => $appointment->note,
        "recurring"      => $appointment->recurring,
        "repeat_until"   => $appointment->repeat_until,
      ];
   }

   return $items;
  }


  public function leaveDayAppointmentsToJson( $appointments )
  {
    $items = [];

    foreach ($appointments as $key => $appointment)
    {
      $title = ( $user = $this->getUserObject( $appointment->user_id ) ) ? $user->getCalendarName() : null;

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
        "type_text"      => $appointment->getTypeHumanReadable(),
        "note"           => $appointment->note,
        "recurring"      => $appointment->recurring,
        "repeat_until"   => $appointment->repeat_until,
      ];
    }

    return $items;
  }


  public function schoolDayAppointmentsToJson( $appointments )
  {
    $items = [];

    foreach ($appointments as $key => $appointment)
    {
      $title = ( $user = $this->getUserObject( $appointment->user_id ) ) ? $user->getCalendarName(" (BS)") : null;

      $items[] =
      [
        "id"             => $appointment->id,
        "type_class"     => "school-day",
        "date_from"      => formatDate( $appointment->date_from, $format="Y-m-d" ),
        "time_from"      => formatDate( $appointment->date_from, $format="H:i" ),
        "date_to"        => formatDate( $appointment->date_to, $format="Y-m-d" ),
        "time_to"        => formatDate( $appointment->date_to, $format="H:i" ),
        "title"          => $title,
        "description"    => null,
        "location_id"    => null,
        "user_id"        => $appointment->user_id,
        "type"           => $appointment->type,
        "type_text"      => $appointment->getTypeHumanReadable(),
        "note"           => $appointment->note,
        "recurring"      => $appointment->recurring,
        "repeat_until"   => $appointment->repeat_until,
      ];
    }

    return $items;
  }


  public function privateAppointmentsToJson( $appointments )
  {
    $items = [];

    foreach ($appointments as $key => $appointment)
    {
      $title = ( $user = $this->getUserObject( $appointment->user_id ) ) ? $user->getCalendarName() : null;

      $items[] =
      [
        "id"             => $appointment->id,
        "type_class"     => "private",
        "date_from"      => formatDate( $appointment->date_from, $format="Y-m-d" ),
        "time_from"      => formatDate( $appointment->date_from, $format="H:i" ),
        "date_to"        => formatDate( $appointment->date_to, $format="Y-m-d" ),
        "time_to"        => formatDate( $appointment->date_to, $format="H:i" ),
        "title"          => $title,
        "description"    => null,
        "location_id"    => null,
        "user_id"        => $appointment->user_id,
        "type"           => $appointment->type,
        "type_text"      => $appointment->getTypeHumanReadable(),
        "note"           => $appointment->note,
        "recurring"      => $appointment->recurring,
        "repeat_until"   => $appointment->repeat_until,
      ];
    }

    return $items;
  }


  public function sickAppointmentsToJson( $appointments )
  {
    $items = [];


    foreach ($appointments as $key => $appointment)
    {
      $title = ( $user = $this->getUserObject( $appointment->user_id ) ) ? $user->getCalendarName() : null;

      $items[] =
      [
        "id"             => $appointment->id,
        "type_class"     => "sick",
        "date_from"      => formatDate( $appointment->date_from, $format="Y-m-d" ),
        "time_from"      => formatDate( $appointment->date_from, $format="H:i" ),
        "date_to"        => formatDate( $appointment->date_to, $format="Y-m-d" ),
        "time_to"        => formatDate( $appointment->date_to, $format="H:i" ),
        "title"          => $title,
        "description"    => null,
        "location_id"    => null,
        "user_id"        => $appointment->user_id,
        "type"           => $appointment->type,
        "type_text"      => $appointment->getTypeHumanReadable(),
        "note"           => $appointment->note,
        "recurring"      => $appointment->recurring,
        "repeat_until"   => $appointment->repeat_until,
      ];
    }



    return $items;
  }


  public function freeAppointmentsToJson( $appointments )
  {

   $items = [];

   foreach ($appointments as $key => $appointment)
   {
    $title = ( $user = $this->getUserObject( $appointment->user_id ) ) ? $user->getCalendarName("(frei)") : null;

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
      "type_text"      => $appointment->getTypeHumanReadable(),
      "note"           => $appointment->note,
      "recurring"      => $appointment->recurring,
      "repeat_until"   => $appointment->repeat_until,
    ];
   }

   return $items;
  }



  public function workAppointmentsToJson( $appointments )
  {

   $items = [];

   foreach ($appointments as $key => $appointment)
   {
      $user = $this->getUserObject( $appointment->user_id );

      $location = $this->getLocationObject($appointment->location_id);
      $items[] =
      [
        "id"             => $appointment->id,
        "type_class"     => "work",
        "date_from"      => date("Y-m-d", strtotime($appointment->date_from) ),
        "time_from"      => date("H:i", strtotime($appointment->date_from) ),
        "date_to"        => date("Y-m-d", strtotime($appointment->date_to) ),
        "time_to"        => date("H:i", strtotime($appointment->date_to) ),
        "title"          => ($user) ? $user->getCalendarName() : null,

        "description"    => $appointment->description,
        "location_id"    => $appointment->location_id,

        "user_id"          => $appointment->user_id,
        "type"             => $appointment->type,
        "type_text"        => $appointment->getTypeHumanReadable(),
        "note"             => $appointment->note,

        "parent_id"        => $appointment->parent_id,
        "recurring_dates"  => $appointment->recurring_dates,

        "tooltip_title"    => ($user) ? $user->getFullName() : null,
        "tooltip_location" => ($location) ? $location->name : null,

        "recurring"        => $appointment->recurring,
        "repeat_until"     => $appointment->repeat_until,
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