<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FutureEventsApiController extends Controller
{
  public function index( Request $request )
  {

    $type = $request->get("type");

    if ($type === 'weekly') {
      return $this->weekly($request);
    } elseif ($type === 'daily') {
      return $this->daily($request);
    }
  }

  public function weekly( Request $request )
  {
    $from = $request->get("from");
    $to = $request->get("to");

    $data = null;

    if ( $from && $to )
    {
      $from_date = Carbon::parse($from);
      $to_date = Carbon::parse($to);

      for ($date=$from_date; $date->lte($to_date); $date->addWeek() )
      {
        $data[] = sprintf("%s %s", $date->locale('de')->shortDayName, $date->format('d.m.Y'));
      }

    }

    if ( count($data) > 5 )
    {
      $tmp[] = $data[0];
      $tmp[] = $data[1];
      $tmp[] = $data[2];
      $tmp[] = "...";
      $tmp[] = end($data);
      $data = $tmp;
    }

    return response()->json( $data );
  }


  public function daily( Request $request )
  {
    $from = $request->get("from");

    $data = null;

    if ($from) {
      $from_date = Carbon::parse($from);

      $weekStartDate = $from_date->startOfWeek();
      $weekEndDate = $from_date->endOfWeek();

      $i = 0;
      for ($date=$from_date->startOfWeek(); $date->lte($weekEndDate); $date->addDay() )
      {
        $i++;

        $data[$date->format('Y-m-d')] = sprintf("%s %s", $date->locale('de')->shortDayName, $date->format('d.m'));

        if ($i > 6) {
          break;
        }
      }

    }

    return response()->json( $data );
  }


} // end of class