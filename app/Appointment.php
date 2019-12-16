<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

  public function user()
  {
    return $this->belongsTo("App\User");
  }


  public function location()
  {
    return $this->belongsTo("App\Location");
  }


  public function scopeUserIds( $query, $user_ids )
  {
    if ( is_array($user_ids) && !empty($user_ids) )
    {
      return $query->whereIn( "user_id", $user_ids );
    }
    else
    {
      return $query;
    }
  }



  public function scopeLocationIds( $query, $location_ids )
  {
    if ( is_array($location_ids) && !empty($location_ids) )
    {
      return $query->whereIn( "location_id", $location_ids );
    }
    else
    {
      return $query;
    }
  }


  public function scopeEvents( $query )
  {
    return $query->whereType(2);
  }


  public function scopeLeaveDays( $query )
  {
    return $query->whereType(1);
  }


  public function scopeHolidayFlat( $query )
  {
    return $query->whereType(3);
  }


  public function scopeWork( $query )
  {
    return $query->whereType(4);
  }


  public function scopeNoLocation( $query )
  {
    return $query->whereLocationId(null);
  }


  public function scopeDateFromBetween( $query, $from, $to )
  {
    return $query->whereBetween('date_from', [$from, $to]);
  }


  public function scopeDateFrom( $query, $date )
  {
    $date_from =
    [
      ["date_from", ">=", $date." 00:00:00"],
      ["date_from", "<=", $date." 24:00:00"],
    ];

    // dd($date_from);
    return $query->where($date_from);
  }


  public function leaveDayToJson()
  {

  }


}
