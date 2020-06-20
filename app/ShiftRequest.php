<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftRequest extends Model
{
  protected $fillable = ["type", "date_from", "date_to", "time_from", "time_to", "note" ];


  public function user()
  {
    return $this->belongsTo("App\User");
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


  public function scopeUserId( $query, $user_id )
  {
    if ( $user_id && is_numeric($user_id) )
    {
      return $query->where( "user_id", $user_id );
    }
    else
    {
      return $query;
    }
  }


  public function scopeType( $query, $type )
  {
    if ( $type && is_numeric($type) )
    {
      return $query->where( "type", $type );
    }
    else
    {
      return $query;
    }
  }

  public function scopeStatus( $query, $status )
  {
    if ( is_numeric($status) )
    {
      return $query->where( "status", $status );
    }
    else
    {
      return $query;
    }
  }


  public function saveEntry( $request )
  {
    $user = \Auth::user();

    $time_from = ( $tf = $request->get("time_from") ) ? $tf : config("appointment.day_start");
    $time_to   = ( $tt = $request->get("time_to") ) ? $tt : config("appointment.day_end");

    $this->user_id        = ($this->user_id) ? $this->user_id : $user->id;
    $this->type           = $request->get("type");

    $date_from            = formatDate($request->get("date_from"), $format = "Y-m-d");
    $date_to              = formatDate($request->get("date_to"), $format = "Y-m-d" );
    $this->date_from      = $date_from." ".$time_from;
    $this->date_to        = $date_to." ".$time_to;

    $this->note           = $request->get("note");
    $this->edited_by      = $user->id;

    $status = $request->get("status");
    if ( $status && $user->isAdmin() )
    {
      $this->status = $status;
    }


    $this->save();

    return true;
  }


  public function getTypeHumanReadable()
  {
    $types = config("shift-request.type");

    if ( $this->type && isset($types[$this->type]) )
    {
      return $types[$this->type]["text"];
    }
    else
    {
      return null;
    }
  }

}
