<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Appointment;
use App\Exports\AppointmentExport;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AppointmentApiRepository
{

  public function store( Request $request )
  {
    $appointment = new Appointment();

    $saved = $appointment->saveEntry( $request );

    if ( $saved && $appointment->id && is_numeric($appointment->id) )
    {
      $message = "Termin wurde aktualisiert.";

      if ( $this->maybeTriggerEvent($request, $appointment) && is_object($appointment->user) )
      {
        $message .=  sprintf( " Benachrichtiung an: %s", $appointment->user->email );
      }

      return ["status"=> "ok", "message"=>$message, "id"=> $appointment->id ];
    }
    else
    {
      return ["status"=> "error", "message"=>"Mitarbeiter nicht verfügbar", "item"=> null ];
    }
  }



  public function update( Request $request, $appointment_id )
  {
    $appointment = Appointment::findOrFail($appointment_id);
    $saved = $appointment->saveEntry( $request );

    if ( $saved && $appointment->id && is_numeric($appointment->id) )
    {
      $message = "Termin wurde aktualisiert.";

      if ( $appointment->user->email )
      {
        if ( $this->maybeTriggerEvent($request, $appointment) && is_object($appointment->user) )
        {
          $message .=  sprintf(" Benachrichtiung an: %s", $appointment->user->email);
        }
      }
      else
      {
        $message .=  sprintf(" Noch keine E-Mail hinterlegt.");
      }


      return ["status"=> "ok", "message"=>$message, "id"=> $appointment->id ];
    }
    else
    {
      return ["status"=> "error", "message"=>"Mitarbeiter nicht verfügbar", "item"=> null ];
    }
  }


  public function maybeTriggerEvent( Request $request, Appointment $appointment )
  {
    $type = $request->get("action");
    if ( $type === "email" or $type === "sms" )
    {
      event( new \App\Events\AppointmentSavedEvent($appointment, $type ) );
      return true;
    }
    else
    {
      return false;
    }
  }


  public function destroy( $appointment_id )
  {
    $appointment = Appointment::findOrFail($appointment_id);
    $appointment->delete();

    return ["status"=> "ok", "message"=>"Termin wurde entfernt", "id"=> $appointment->id ];
  }


  public function index( Request $request )
  {
    $calendar = new AppointmentExport();
    $data = $calendar->index($request);

    return $data;
  }


}