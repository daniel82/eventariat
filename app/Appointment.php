<?php

namespace App;

use Illuminate\Support\Facades\Log;
use App\ShiftRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
  // relationships
  //
  protected $fillable = ["shift_request_id"];

  public function user()
  {
    return $this->belongsTo("App\User");
  }


  public function location()
  {
    return $this->belongsTo("App\Location");
  }


  // common update method
  public function saveEntry( $request )
  {
    Log::debug("Appointment@saveEntry");
    $user = \Auth::user();
    Log::debug($user);
    $this->location_id    = $request->get("location_id");
    $this->user_id        = $request->get("user_id");
    $this->type           = $request->get("type");
    $this->description    = $request->get("description");
    $this->date_from      = $request->get("date_from")." ".$request->get("time_from");
    $this->date_to        = $request->get("date_to")." ".$request->get("time_to");
    $this->note           = $request->get("note");
    $this->edited_by      = $user->id;
    $this->created_by     = ($this->id) ? $this->created_by : $user->id;


    if ( !$this->isUserBooked($this->user_id, $this->date_from, $this->date_to, $this->id) )
    {
      $this->save();
      return true;
    }
    else
    {
      return false;
    }
  }


  public function assumeShiftRequest( ShiftRequest $shiftRequest )
  {
    Log::debug("Appointment@assumeShiftRequest");
    $user = \Auth::user();
    $this->shift_request_id   = $shiftRequest->id;
    $this->type               = $shiftRequest->type;
    $this->date_from          = $shiftRequest->date_from." 00:00:00";
    $this->date_to            = $shiftRequest->date_to." 23:59:00";
    $this->edited_by          = $user->id;
    $this->created_by         = ($this->id) ? $this->created_by : $user->id;
    $this->user_id            = $shiftRequest->user_id;


    $this->save();
  }


  public function isUserBooked( $user_id, $date_from, $date_to, $appointment_id )
  {
    // Log::info("is user booked?");
    // Log::info($user_id);
    // Log::info($date_from);
    // Log::info($date_to);
    // Log::info($appointment_id);
    $is_booked = false;
    if ( $user_id )
    {
      $appointments = Appointment::idNotIn( $appointment_id )->userId($user_id)->period($date_from, $date_to)->get();
      // $appointments = Appointment::userId($user_id)->period($date_from, $date_to)->get();

      if ( $appointments && $appointments->count() )
      {
        // Log::debug("appointment");
        // Log::debug($appointments);
        $is_booked = true;
      }
    }

    return $is_booked;
  }



  // scopes
  //
  //
  public function scopeIdNotIn( $query, $appointment_id )
  {
    if ( $appointment_id )
    {
      return $query->whereNotIn("id", [$appointment_id] );
    }
    else
    {
      return $query;
    }


  }

  // public function scopePeriod( $query, $date_from, $date_to )
  // {
  //   return $query->whereBetween('date_from', [$date_from, $date_to])
  //           ->orWhereBetween('date_to', [$date_from, $date_to]);
  // }

  public function scopePeriod( $query, $date_from, $date_to )
  {
    return $query->whereRaw('(date_from <= ? AND date_to >= ? OR date_from <= ? AND date_to >= ?)', [$date_from, $date_from, $date_to, $date_to]);
  }


  public function scopeUserId( $query, $user_id )
  {
    return $query->whereUserId( $user_id );
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

  public function scopeVarious( $query )
  {
    return $query->whereType(5);
  }

  public function scopeFree( $query )
  {
    return $query->whereType(6);
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
