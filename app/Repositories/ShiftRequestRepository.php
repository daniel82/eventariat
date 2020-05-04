<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\ShiftRequest;
use App\User;

class ShiftRequestRepository
{

    public function index( Request $request )
    {
        $user = \Auth::user();
        $data["users"] = User::orderBy("first_name", "asc")->orderBy("last_name", "asc")->get();
        $data["types"] = $types =  config("shift-request.type");
        $data["statuses"] = $statuses =  config("shift-request.status");

        $data["status"]   = $request->get("status");
        $data["type"]     = $request->get("type");
        $data["user_id"]  = (int) $request->get("user_id");
        $data["items"] = ShiftRequest::userId($data["user_id"])->type($data["type"])->status($data["status"])->orderBy("created_at", "DESC")->paginate(50);

        foreach ($data["items"] as $key => &$item)
        {
            $item->type_hr = ( isset($types[$item->type]) ) ? $types[$item->type]["text"] : null;
        }

        return $data;
    }


    public function store( Request $request, ShiftRequest $shiftRequest )
    {
        $shiftRequest->saveEntry( $request );
        event( new \App\Events\ShiftRequestCreatedEvent($shiftRequest) );
        return $shiftRequest;
    }


    public function update( Request $request, ShiftRequest $shiftRequest )
    {
        // Log::info("ShiftRequestRepository@update");
        $shiftRequest->saveEntry( $request );

        if ( $shiftRequest->status )
        {
          // Log::info("has status ".$shiftRequest->status);
          if ( $shiftRequest->status == 1 )
          {
            $appointment = \App\Appointment::firstOrNew(["shift_request_id" => $shiftRequest->id]);
            $appointment->assumeShiftRequest($shiftRequest);
            event( new \App\Events\AppointmentSavedEvent($appointment, "email" ) );
          }
        }
        else
        {
          Log::info("no status ".$shiftRequest->status);
        }


        return $shiftRequest;
    }


    public function getFormData( ShiftRequest $shiftRequest )
    {
        $data["object"] = $shiftRequest;
        $data["today"] = date("Y-m-d");

        $current_user = \Auth::user();

        $data["user"] = ( is_object($shiftRequest->user) ) ? $shiftRequest->user : $current_user;

        $data["hours"]   = getHours();


        $data["ev_app_data"] =
        [
          "date_from"      => formatDate( $shiftRequest->date_from, "Y-m-d" ),
          "time_from"      => formatDate( $shiftRequest->date_from, "H:i" ),
          "date_to"        => formatDate( $shiftRequest->date_to, "Y-m-d" ),
          "time_to"        => formatDate( $shiftRequest->date_to, "H:i" ),
          // "date_from"  => $shiftRequest->date_from,
          // "date_to"    => $shiftRequest->date_to,
          // "time_to"    => $shiftRequest->time_to,
          // "time_from"  => $shiftRequest->time_from,
          "note"       => $shiftRequest->note,
          "type"       => ($shiftRequest->type) ? $shiftRequest->type : 1,
          "status"     => ($shiftRequest->status) ? $shiftRequest->status : 0,
          "is_admin"   => $current_user->isAdmin(),
          "is_submitted"   => false,
          "user_id"    => ($shiftRequest->user_id) ? $shiftRequest->user_id : $data["user"]->id,
          "show_alert" => false,
          "alert_message" => false,
          "count_appointments" => 0,
        ];


        return $data;
    }


}