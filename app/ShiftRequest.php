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
