<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\ShiftRequest;

class ShiftRequestRepository
{

  public function index( Request $request )
  {
    $data["user"] = $user = \Auth::user();

    $user_ids = ( $user->isAdmin() ) ? [] : [$user->id];

    $data["items"] = ShiftRequest::userIds($user_ids)->orderBy("created_at")->paginate(50);

    $types =  config("shift-request.type");
    foreach ($data["items"] as $key => &$item)
    {
      $item->type_hr = ( isset($types[$item->type]) ) ? $types[$item->type]["text"] : null;
    }

    return $data;
  }


  public function store( Request $request, ShiftRequest $shiftRequest )
  {
    $shiftRequest->saveEntry( $request );
    return $shiftRequest;
  }


  public function update( Request $request, ShiftRequest $shiftRequest )
  {
    Log::info("ShiftRequestRepository@update");


    $shiftRequest->saveEntry( $request );

    if ( $shiftRequest->status )
    {
      Log::info("has status ".$shiftRequest->status);
      if ( $shiftRequest->status == 1 )
      {
        $appointment = \App\Appointment::firstOrNew(["shift_request_id" => $shiftRequest->id]);
        $appointment->assumeShiftRequest($shiftRequest);
        // store or update appointment
        // // event( new \App\Events\AppointmentSavedEvent($appointment, "email" ) );
      }
    }
    {
      Log::info("no status ".$shiftRequest->status);
    }


    return $shiftRequest;
  }


  public function getFormData( ShiftRequest $shiftRequest )
  {
    $data["object"] = $shiftRequest;
    $data["today"] = date("Y-m-d");
    $data["user"] = \Auth::user();
    $data["ev_app_data"] =
    [
      "date_from"  => $shiftRequest->date_from,
      "date_to"    => $shiftRequest->date_to,
      "note"       => $shiftRequest->note,
      "type"       => ($shiftRequest->type) ? $shiftRequest->type : 1,
      "status"     => ($shiftRequest->status) ? $shiftRequest->status : 0,
      "is_admin"   => $data["user"]->isAdmin(),
    ];


    return $data;
  }


  public function sanitizeRequest( Request $request )
  {


    return $request;
  }



}