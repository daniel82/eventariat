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

      // if ( $this->maybeTriggerEvent($request, $appointment) && is_object($appointment->user) )
      // {
      //   $message .=  sprintf( " Benachrichtiung an: %s", $appointment->user->email );
      // }

      if ( $email = $appointment->userEmail() )
      {
        if ( $this->maybeTriggerEvent($request, $appointment, "saved") )
        {
          $message .=  sprintf(" Benachrichtiung an: %s", $email);
        }
      }
      else
      {
        $message .=  sprintf("Noch keine E-Mail hinterlegt.");
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
    Log::info("AppointmentApiRepository@update: ".$appointment_id);
    $appointment = Appointment::findOrFail($appointment_id);

    $user = \Auth::user();
    if ( is_object($user) ) {
      Log::info("updated by: ".$user->id);
    }
    Log::info("user_id: ".$appointment->user_id);
    Log::debug($request->all());
    $saved = $appointment->saveEntry( $request );

    if ( $saved && $appointment->id && is_numeric($appointment->id) )
    {
      $message = "Termin wurde aktualisiert.";


      if ( $email = $appointment->userEmail() )
      {
        if ( $this->maybeTriggerEvent($request, $appointment, "saved") )
        {
          $message .=  sprintf(" Benachrichtiung an: %s", $email );
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


  public function maybeTriggerEvent( Request $request, Appointment $appointment, $event_type="saved" )
  {
    $type = $request->get("action");
    if ( $type === "email" or $type === "sms" )
    {

      if ( $event_type === "saved" )
      {
        event( new \App\Events\AppointmentSavedEvent($appointment, $type ) );
      }
      else if ( $event_type === "destroyed" )
      {
        event( new \App\Events\AppointmentDestroyedEvent($appointment, $type ) );
      }

      return true;
    }
    else
    {
      return false;
    }
  }


  public function destroy( Request $request, $appointment_id )
  {
    Log::info("AppointmentApiRepository@destroy");
    $appointment = Appointment::findOrFail($appointment_id);

    $user = \Auth::user();
    if ( is_object($user) ) {
      Log::info($appointment->id." deleted by: ".$user->id);
      Log::info("user_id: ".$appointment->user_id);
      Log::info("location_id: ".$appointment->location_id);
      Log::info($appointment->date_from."/".$appointment->date_to);
    }

    $message = "Termin wurde entfernt";
    if ( $email = $appointment->userEmail() )
    {
      if ( $this->maybeTriggerEvent($request, $appointment, "destroyed") )
      {
        $message .=  sprintf(" Benachrichtiung an: %s", $email);
      }
    }
    else
    {
       $message .=  sprintf(" Noch keine E-Mail hinterlegt.");
    }


    $appointment->delete();
    return ["status"=> "ok", "message"=>$message, "id"=> $appointment->id ];
  }


  public function index( Request $request )
  {
    $calendar = new AppointmentExport();
    $data = $calendar->index($request);

    return $data;
  }


}