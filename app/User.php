<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Notifications\Notifiable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use SoftDeletes;
  use Notifiable;


  protected $casts = [
    // 'appointment_types' => 'object', //TODO JSON
    'email_verified_at' => 'datetime',
  ];


  public function getAppointmentTypesAttribute($value)
  {
    return ( is_string($value) && $value) ? unserialize($value) : [];
  }

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable =
  [
    'first_name', 'last_name', 'birthdate',

    'email', 'phone', 'mobile',

    'street', 'zipcode', 'city',

    'password' ,

    'leave_days', 'hours_of_work',

    'remaining_leave', 'disposable_until',

    'role', 'employment',

    'can_see_other_appointments',
    'appointment_types',
  ];


  public function locations()
  {
    return $this->belongsToMany("App\Location");
  }


  public function setMobileAttribute( $value )
  {
    if ( trim($value) )
    {
      if ( $value[0] === "0" )
      {
        // remove white spaces
        $number = str_replace(" ", null, $value);
        // add german country number
        $this->attributes['mobile'] = "+49".substr($value, 1 );
      }
    }
  }


  public function setHoursOfWorkAttribute( $value )
  {
    $this->attributes['hours_of_work'] = ($value) ? $value : 0;
  }


  public function setLeaveDaysAttribute( $value )
  {
    $this->attributes['leave_days'] = ($value) ? $value : 0;
  }


  public function getTotalLeaveDays()
  {
    $leave_days = $this->leave_days;

    if ( is_numeric($this->remaining_leave) && $this->disposable_until )
    {
      $year = date("Y");
      $first_day_of_current_year = $year."-01-01";
      $last_day_of_current_year = $year."-12-31";

      if ( $this->disposable_until >= $first_day_of_current_year && $this->disposable_until <= $last_day_of_current_year )
      {
        $leave_days += $this->remaining_leave;
      }
      elseif ( $this->disposable_until < $first_day_of_current_year)
      {
        $this->disposable_until = null;
        $this->remaining_leave  = null;
      }
    }


    return $leave_days;
  }


  public function shiftRequests()
  {
    return $this->hasMany("App\ShiftRequest");
  }



  public function getCalendarName( $suffix = null)
  {
    return $this->first_name." ".$this->last_name[0].".".$suffix;
  }

  public function getFullName()
  {
   return $this->first_name." ".$this->last_name;
  }


  public function scopeBirthdate( $query, $from, $to=null )
  {
    $from = ( $from > 1 ) ? $from : 1;

    if ( $to > $from )
    {
      return $query->whereRaw( 'extract(month from birthdate) >= ? AND extract(month from birthdate) <= ?  ', [$from, $to] );
    }
    else
    {
      return $query->whereRaw( 'extract(month from birthdate) >= ? OR extract(month from birthdate) <= ?  ', [$from, $to] );
    }

  }


  public static function getAllAsJson()
  {
    $users = User::all();

    $json = [];
    foreach ($users as $key => $user)
    {
      $json[] =
      [
        "label"    => $user->first_name." ".$user->last_name,
        "id"       => $user->id
      ];
    }

    return $json;
  }


  public function canSee( $appointment_type )
  {
    return in_array($appointment_type, $this->appointment_types);
  }


  public function isAdmin()
  {
    return ($this->role === "admin");
  }


  public function isPermanent()
  {
    return ($this->employment === "permanent");
  }

  public function isTraining()
  {
    return ($this->employment === "training");
  }


  public static function current()
  {
    return \Auth::user();
  }


  public static function makeDefaultPassword( $last_name )
  {
    $hash = md5( $last_name );
    return substr($hash, 0, 8);
  }


  public function getAppointments( $date_from, $date_to )
  {
    return \App\Appointment::userId($this->id)->period($date_from, $date_to)->get();
  }


  public function appointments()
  {
    return $this->hasMany("App\Appointment");
  }


  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];

}
