<?php

namespace App;

use Illuminate\Support\Facades\Log;
use App\ShiftRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
  // relationships
  //
  protected $fillable = ["shift_request_id"];

  public function user()
  {
    return $this->belongsTo("App\User");
  }


  public function userEmail()
  {
    return ( is_object($this->user) && isset($this->user->email) && $this->user->email ) ? $this->user->email : null;
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
    Log::debug($request->all());

    $time_from = ( $tf = $request->get("time_from") ) ? $tf : config("appointment.day_start");
    $time_to   = ( $tt = $request->get("time_to") ) ? $tt : config("appointment.day_end");

    $date_from = $request->get("date_from");

    $this->location_id    = $request->get("location_id");
    $this->user_id        = $request->get("user_id");
    $this->type           = $request->get("type");
    $this->description    = $request->get("description");
    $this->date_from      = $date_from." ".$time_from;
    $this->date_to        = $request->get("date_to")." ".$time_to;
    $this->note           = $request->get("note");
    $this->recurring      = $request->get("recurring", null);
    $this->edited_by      = $user->id;
    $this->created_by     = ($this->id) ? $this->created_by : $user->id;
    $this->repeat_until   = $request->get("repeat_until", null);
    $this->recurring_dates= $request->get("recurring_dates", []);


    // set location id null if appointment not "arbeit"
    if ($this->type != 4) {
      $this->location_id = null;
    }

    // set user id null if appoint ereignis/event or ferienwohnung
    if( $this->type==2 or $this->type == 3 ) {
      $this->user_id = null;
    }

    // excepction for recurring events
    // check if is recurring && if user is booked
    $future_events = null;
    if ( $this->recurring==='weekly' && $this->repeat_until) {
      $future_events = $this->getRecurringFutureEvents($time_from, $time_to);
    } else if ($this->recurring==='daily' && !empty($this->recurring_dates)) {
      // prepare recurrig dates as future events for saving
      $future_events = $this->getRecurringDates($this->recurring_dates, $time_from, $time_to, $date_from);
    }

    // Log::info('id?');
    // Log::info($this->id);
    // Log::info($future_events);

    if ( $future_events )
    {
      foreach ($future_events as $future_date)
      {
        $children_ids = ($this->id) ? Appointment::whereParentId($this->id)->pluck("id") : [];
        // Log::debug("children_ids");
        // Log::debug($children_ids);
        if ( $this->isUserBooked($this->user_id, $future_date["from"], $future_date["to"], $children_ids) )
        {
          return false;
        }
      }
    }

    if ( !$this->isUserBooked($this->user_id, $this->date_from, $this->date_to, $this->id) )
    {
      $this->save();

      $this->saveChildren( $future_events );
      return true;
    }
    else
    {
      return false;
    }
  }

  public function isUserBooked( $user_id, $date_from, $date_to, $appointment_id )
  {
    $is_booked = false;
    if ( $user_id )
    {
      $appointments = Appointment::idNotIn($appointment_id)->userId($user_id)->period($date_from, $date_to)->get();
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


  public function saveChildren( $future_events )
  {
    if( !$this->recurring )
    {
      return null;
    }
    else
    {
      $this->storeChildren($future_events);
    }
  }


  public function storeChildren( $future_events )
  {
    // Log::debug("store children ...");
    // Log::debug($future_events);
    // delete all children
    Appointment::whereParentId($this->id)->delete();

    if ( is_array($future_events) )
    {
      foreach ($future_events as $key => $future_date)
      {
        $child = new Appointment();

        // custom date
        $child->date_from   = $future_date["from"];
        $child->date_to     = $future_date["to"];

        // copy values from parent
        $child->location_id = $this->location_id;
        $child->user_id     = $this->user_id;
        $child->type        = $this->type;
        $child->description = $this->description;
        $child->note        = $this->note;
        $child->edited_by   = $this->edited_by;
        $child->created_by  = $this->created_by;
        $child->parent_id   = $this->id;

        $child->save();
      }
    }
  }


  public function getRecurringFutureEvents($time_from, $time_to)
  {
    Log::debug("get future events");
    $from_date = Carbon::parse($this->date_from);
    $to_date = Carbon::parse($this->repeat_until." ".$time_from);

    Log::debug("from: ".$from_date);
    Log::debug("to: ".$to_date);
    $dates = null;

    for ($date=$from_date; $date->lte($to_date); $date->addWeek() )
    {
      Log::debug($date->format("Y-m-d H:i")." : ".$this->date_from);
      if ( (string) $date->format("Y-m-d H:i") !== (string)$this->date_from )
      {
        $dates[] =
        [
          "from" => $date->format('Y-m-d')." ".$time_from,
          "to" => $date->format('Y-m-d')." ".$time_to,
        ];
      }
    }

    return $dates;
  }

  public function getRecurringDates($recurring_dates, $time_from, $time_to, $parent_date)
  {
    // Log::debug("prepare recurring_dates");
    // Log::debug($parent_date);
    // Log::debug($time_from);
    // Log::debug($time_to);
    $dates = [];

    foreach ($recurring_dates as $date) {
      Log::debug($date);
      if ($date != $parent_date) {
        $dates[] =
          [
            "from" => $date." ".$time_from,
            "to" => $date." ".$time_to,
          ];
      }
    }

    return $dates;
  }


  public function assumeShiftRequest( ShiftRequest $shiftRequest )
  {
    Log::debug("Appointment@assumeShiftRequest");
    $user = \Auth::user();
    $this->shift_request_id   = $shiftRequest->id;
    $this->type               = $shiftRequest->type;
    $this->date_from          = $shiftRequest->date_from;
    $this->date_to            = $shiftRequest->date_to;
    $this->edited_by          = $user->id;
    $this->created_by         = ($this->id) ? $this->created_by : $user->id;
    $this->user_id            = $shiftRequest->user_id;


    $this->save();
  }


  // scopes
  public function scopePeriod( $query, $date_from, $date_to )
  {
    return $query->whereRaw('(date_from <= ? AND date_to >= ? OR date_from <= ? AND date_to >= ? OR date_from <= ? AND date_to >= ? OR date_from >= ? AND date_to <= ?)', [$date_from, $date_from, $date_to, $date_to, $date_from, $date_to, $date_from, $date_to]);
    // return $query->whereRaw('(date_from <= ? AND date_to >= ? OR date_from <= ? AND date_to >= ?)', [$date_from, $date_from, $date_to, $date_to]);
    // return $query->whereRaw('(date_from <= ? AND date_to >= ? OR date_from >= ? AND date_to <= ?)', [$date_from, $date_to, $date_from, $date_to]);
  }


  public function scopeHours( $query, $date_from, $date_to )
  {

    return $query->whereRaw('(date_from >= ? AND date_to <= ?)', [$date_from, $date_to]);
  }


  public function scopePeriodBetween( $query, $date_from, $date_to )
  {
    return $query->whereRaw('(date_from >= ? AND date_to < ? OR date_to >= ? AND date_to < ?)', [$date_from, $date_to, $date_from, $date_to]);
  }


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


  public function scopeUserId( $query, $user_id )
  {
    return $query->whereUserId( $user_id );
  }


  public function scopeUserIds( $query, $user_ids )
  {
    // $user_ids = (is_numeric($user_ids) ) ?

    if ( is_array($user_ids) && !empty($user_ids) )
    {
      return $query->whereIn( "user_id", $user_ids );
    }
    else
    {
      return $query;
    }
  }


  public function scopeTypeIds( $query, $type_ids )
  {
    // $user_ids = (is_numeric($user_ids) ) ?

    if ( is_array($type_ids) && !empty($type_ids) )
    {
      return $query->whereIn( "type", $type_ids );
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

  public function scopePrivateDates( $query )
  {
    return $query->whereType(5);
  }

  public function scopeSick( $query )
  {
    return $query->whereType(7);
  }

  public function scopeSchool( $query )
  {
    return $query->whereType(8);
  }



  public function scopeNoLocation( $query )
  {
    return $query->whereLocationId(null);
  }


  public function scopeDateFromBetween( $query, $from, $to )
  {
    $to = $to." ".config("appointment.day_end");
    return $query->whereBetween('date_from', [$from, $to]);
  }


  public function scopeDateFrom( $query, $date )
  {
    $date_from =
    [
      ["date_from", ">=", $date." ".config("appointment.day_start")],
      ["date_from", "<=", $date." ".config("appointment.day_end")],
    ];

    // dd($date_from);
    return $query->where($date_from);
  }


  public function getTypeHumanReadable()
  {
    $types = config("appointment.type");
    $type = null;

    foreach ($types as $key => $row)
    {
      if ( $row["id"] == $this->type )
      {
        $type = $row["text"];
        break;
      }
    }

    return $type;
  }


  public function getRecurringDatesAttribute($value)
  {
    if (is_string($value) && trim($value)) {
      return json_decode($value);
    } else {
      return null;
    }
  }

  public function setRecurringDatesAttribute($value)
  {
    if (is_array($value) && !empty($value)) {
      $this->attributes['recurring_dates'] = json_encode($value);
    } else {
      return null;
    }
  }


} // end of class