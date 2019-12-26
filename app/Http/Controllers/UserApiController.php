<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

use App\Repositories\UserRepository;
use App\Appointment;
use App\User;
use Carbon\Carbon;

use Illuminate\Contracts\Auth\Authenticatable;


class UserApiController extends Controller
{

    public function __construct( UserRepository $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show( Request $request, $id)
    {
        $current_user = \Auth::user();
        $data=null;

        if ( $current_user->isAdmin() or $current_user->id == $id )
        {
            $date = $request->get("date");
            $now = strtotime($date);
            $year = date("Y", $now);

            $user = User::find($id);

            $data["leave_days"]          = $user->leave_days;
            $data["hours_of_work"]       = $user->hours_of_work;
            $data["leave_days_intended"] = 0;
            $data["work_load_this_week"] = 0;
            $data["tooltip_leave_days"]  = null;
            $data["tooltip_work_load"]   = null;

            $year_start = $year."-01-01";
            $year_end   = ($year+1)."-12-31";

            // calculate holidays
            if ( $holidays = Appointment::leaveDays()->userId($user->id)->periodBetween($year_start, $year_end)->get() )
            {

                foreach ( $holidays as $key => $holiday)
                {
                    $date_from = Carbon::create($holiday->date_from);
                    $date_to = Carbon::create($holiday->date_to);
                    // $diff = $date_from->diffInDays($date_to);

                    $diff = $date_from->diffInDaysFiltered(function(Carbon $date) use($year)
                    {
                       return ($date->year == $year);
                    }, $date_to);


                    $data["leave_days_intended"] += ($diff);
                }
            }


            // calculate weekly work load
            $week_start   = date("Y-m-d", strtotime('monday this week', $now ) );
            $week_end     = date("Y-m-d", strtotime('sunday this week', $now ) );
            if ( $working_days = Appointment::work()->userId($user->id)->hours($week_start, $week_end)->get() )
            {
                foreach ( $working_days as $key => $work)
                {
                    $date_from = Carbon::create($work->date_from);
                    $date_to = Carbon::create($work->date_to);
                    $diff = ($date_from->diffInMinutes($date_to)/60);

                    if ( $diff > 6.5 )
                    {
                        $diff -= 0.5;
                    }
                    $data["work_load_this_week"] += $diff;
                }
            }


            // add calculated values to response
            if (  is_numeric($data["leave_days"]) && is_numeric($data["leave_days_intended"]) )
            {
                $data["tooltip_leave_days"] = sprintf("%s/%s", $data["leave_days_intended"], $data["leave_days"] );
            }

            if (  is_numeric($data["hours_of_work"]) && is_numeric($data["work_load_this_week"]) )
            {
                $data["tooltip_work_load"] = sprintf("%s/%s", $data["work_load_this_week"], $data["hours_of_work"] );
            }

        }

        return response()->json($data);
    }


    public function appointments( Request $request, $user_id )
    {
        $user = User::findOrFail($user_id);
        $date_from = $request->get("date_from");
        $date_to = $request->get("date_to");
        if ( $date_from && $date_to )
        {
            $data = $user->getAppointments( $date_from, $date_to );
        }
        else
        {
            $data = $user->appointments;
        }

        return response()->json($data);


    }


}
