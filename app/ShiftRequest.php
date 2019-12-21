<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShiftRequest extends Model
{
  protected $fillable = ["type", "date_from", "date_to", "note" ];


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

    $this->user_id        = ($this->user_id) ? $this->user_id : $user->id;
    $this->type           = $request->get("type");
    $this->date_from      = $request->get("date_from");
    $this->date_to        = $request->get("date_to");
    $this->note           = $request->get("note");
    $this->edited_by      = $user->id;

    $status = $request->get("status");
    if ( $status && $user->isAdmin() )
    {
      // TODO trigger event ShiftRequestStatusChangedEvent
      $this->status = $status;
    }


    $this->save();

    return true;
  }

}
