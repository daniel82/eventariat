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

    // $month
    $date_from      = $request->get("date_from" );
    $date_to        = $request->get("date_to");

    if ( !$date_from )
    {
      $df = new Carbon('first day of this month');
      $date_from = $df->format("Y-m-d");

      $dt = new Carbon('last day of this month');
      $date_to = $dt->format("Y-m-d");
    }


    $from_month      = Carbon::create($date_from)->month;
    $to_month        = Carbon::create($date_to)->month;
    $period          = new CarbonPeriod($date_from, '1 day', $date_to);

    $data = [];

    // birthdays
    $users          = User::birthdate($from_month, $to_month)->get();

    // events without location
    $top_events     = Appointment::events()->noLocation()->dateFromBetween($date_from, $date_to)->orderBy("date_from")->get();

    // FeWo
    $fewo_events    = Appointment::holidayFlat()->dateFromBetween($date_from, $date_to)->orderBy("date_from", "ASC")->orderBy("location_id", "ASC")->get();

    // the users leave days
    $leaveDays      = Appointment::leaveDays()->dateFromBetween($date_from, $date_to)->orderBy("date_from", "ASC")->orderBy("user_id", "ASC")->get();


    // // work
    $data=[];
    foreach ($period as $key => $date)
    {
      $the_date = $date->format("Y-m-d");
      $the_date_start = $date->format("Y-m-d")." 00:00:00";
      $the_date_end = $date->format("Y-m-d")." 24:00:00";
      $data[$the_date] = collect();


      // Urlaub
      $leave_dates = $leaveDays->filter(function ($appointment, $key) use($the_date)
      {
        return ($appointment->date_from >= $the_date && $appointment->date_to <= $the_date);
      });
      if ( $leave_dates )
      {
       $data[$the_date] = $data[$the_date]->merge( $leave_dates );
      }


      // Ereignisse ohne Lokalitaet
      if ( $events = $top_events->whereBetween("date_from", [$the_date_start, $the_date_end] ) )
      {
        $data[$the_date] = $data[$the_date]->merge($events);
      }

      // FeWo
      $fewo_dates = $fewo_events->filter(function ($appointment, $key) use($the_date)
      {
        return ($appointment->date_from >= $the_date && $appointment->date_to <= $the_date);
      });
      if ( $fewo_dates )
      {
       $data[$the_date] = $data[$the_date]->merge( $fewo_dates );
      }


      // Geburtstage
      $birthday_kids = $users->filter(function ($user, $key) use($the_date)
      {
        return ( substr($user->birthdate, 5, 5) ) == substr($the_date, 5, 5) ;
      });

      if ( $birthday_kids )
      {
        $data[$the_date] = $data[$the_date]->merge( $this->brithdayKidsToJson($birthday_kids, $the_date) );
      }


      // Normale Arbeit
      if ( $appointments = Appointment::work()->dateFrom($the_date)->orderBy("date_from", "ASC")->orderBy("location_id", "ASC")->get() )
      {
        $data[$the_date] = $data[$the_date]->merge($appointments);
      }

      # code...
    }

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
        "description"    => $user->first_name." ".$user->last_name,
        "age"            => Carbon::parse($user->birthdate)->diffInYears($date)
      ];
    }



    return $birthdates;
  }


}